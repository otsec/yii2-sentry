<?php
namespace otsec\yii2\sentry;

use yii\web\AssetBundle;

/**
 * @author Artem Belov <razor2909@gmail.com>
 */
class RavenCdnAsset extends AssetBundle
{
    public $baseUrl = 'https://cdn.ravenjs.com/3.10.0/';

    public $js = [
        'raven.min.js',
    ];

    public $jsOptions = [
        'crossorigin' => 'anonymous',
    ];
}
