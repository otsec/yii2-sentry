<?php
namespace otsec\yii2\sentry;

use yii\web\AssetBundle;

/**
 * @author Artem Belov <razor2909@gmail.com>
 */
class RavenBowerAsset extends AssetBundle
{
    public $basePath = '@bower/raven-js/dist';

    public $js = [
        'raven.min.js',
    ];
}
