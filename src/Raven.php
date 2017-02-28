<?php
namespace otsec\yii2\sentry;

use Raven_Client;
use Raven_ErrorHandler;
use Yii;
use yii\base\Component;
use yii\web\View;

/**
 * @author Artem Belov <razor2909@gmail.com>
 */
class Raven extends Component
{
    /**
     * @var boolean whether to catch php errors and exceptions by default.
     */
    public $enableErrorHandler = true;
    /**
     * @var string Sentry DSN string.
     * @see https://docs.sentry.io/quickstart/#about-the-dsn
     */
    public $dsn;
    /**
     * @var array
     * @see https://docs.sentry.io/clients/php/config/#available-settings
     */
    public $options = [];
    /**
     * @var boolean whether to catch javascript errors by default.
     */
    public $enableClientScript = false;
    /**
     * @var array
     * @see https://docs.sentry.io/clients/javascript/config/#optional-settings
     * @see https://docs.sentry.io/clients/javascript/tips/#decluttering-sentry
     */
    public $clientOptions = [];
    /**
     * @var string public DSN for JS library. If empty, it will be generated from private DSN.
     */
    public $publicDsn;
    /**
     * @var string
     */
    public $assetBundle = 'otsec\yii2\sentry\RavenCdnAsset';

    /**
     * @var Raven_Client
     */
    private $_client;
    /**
     * @var Raven_ErrorHandler
     */
    private $_errorHandler;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->enableErrorHandler) {
            $this->registerErrorHandler();
        }

        if ($this->enableClientScript) {
            $this->registerClientScript();
        }
    }

    /**
     * Returns or creates Sentry client.
     *
     * @return Raven_Client
     */
    public function getClient()
    {
        if ($this->_client === null) {
            $this->_client = new Raven_Client($this->dsn, $this->options);
        }

        return $this->_client;
    }

    /**
     * Returns or creates Sentry event handler.
     *
     * @return Raven_ErrorHandler
     */
    public function getErrorHandler()
    {
        if ($this->_errorHandler === null) {
            $this->_errorHandler = new Raven_ErrorHandler($this->getClient());
        }

        return $this->_errorHandler;
    }

    /**
     * Registers php handler to catch all errors and exceptions.
     */
    public function registerErrorHandler()
    {
        $this->getErrorHandler()->registerErrorHandler();
        $this->getErrorHandler()->registerExceptionHandler();
    }

    /**
     * Registers raven JS library.
     */
    public function registerClientScript()
    {
        $dsn = $this->publicDsn ?: $this->createPublicDsn($this->dsn);
        $options = json_encode($this->clientOptions);

        $js = "Raven.config('{$dsn}', {$options}).install();";

        Yii::$app->view->registerAssetBundle($this->assetBundle, View::POS_HEAD);
        Yii::$app->view->registerJs($js, View::POS_HEAD);
    }

    /**
     * Log a message to sentry.
     *
     * @param string $message The message (primary description) for the event.
     * @param array $params Params to use when formatting the message.
     * @param array $data Additional attributes to pass with this event.
     * @return string event ID.
     *
     * @see https://docs.sentry.io/clients/php/usage/#reporting-other-errors
     * @see https://docs.sentry.io/clients/php/usage/#optional-attributes
     */
    public function captureMessage($message, $params = [], $data = [])
    {
        return $this->getClient()->captureMessage($message, $params, $data);
    }

    /**
     * Log an exception to sentry.
     *
     * @param \Exception $exception The Exception object.
     * @param array $data Additional attributes to pass with this event.
     * @return string Event ID.
     *
     * @see https://docs.sentry.io/clients/php/usage/#reporting-exceptions
     * @see https://docs.sentry.io/clients/php/usage/#optional-attributes
     */
    public function captureException($exception, $data = [])
    {
        return $this->getClient()->captureException($exception, $data);
    }

    /**
     * Appends additional context to all captured events.
     *
     * @param array $data Associative array of extra data
     */
    public function extraContext(array $data)
    {
        $this->getClient()->extra_context($data);
    }

    /**
     * Appends tags context to all captured events.
     *
     * @param array $data Associative array of tags
     */
    public function tagsContext(array $data)
    {
        $this->getClient()->tags_context($data);
    }

    /**
     * Appends user context to all captured events.
     *
     * @param array $data Associative array of user data
     */
    public function userContext(array $data)
    {
        $this->getClient()->user_context($data);
    }

    /**
     * Cleans up an existing context.
     */
    public function clearContext()
    {
        $this->getClient()->context->clear();
    }

    /**
     * Appends breadcrumb to all captured events.
     *
     * @param array $data
     *
     * @see https://docs.sentry.io/clients/php/usage/#breadcrumbs
     */
    public function addBreadcrumb(array $data)
    {
        $this->getClient()->breadcrumbs->record($data);
    }

    /**
     * Removes private part from full DSN string.
     *
     * @param string $secretDsn
     * @return string
     */
    public function createPublicDsn($secretDsn)
    {
        return preg_replace('#:\w+@#', '@', $secretDsn);
    }
}
