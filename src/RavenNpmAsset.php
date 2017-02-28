<?php
namespace otsec\yii2\sentry;

use yii\web\AssetBundle;

/**
 * @author Artem Belov <razor2909@gmail.com>
 */
class RavenNpmAsset extends AssetBundle
{
    public $basePath = '@npm/raven-js/dist';

    public $js = [
        'raven.min.js',
    ];
}
