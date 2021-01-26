/**
* @license Angular v11.0.0-next.6+37.sha-a38293d.with-local-changes
* (c) 2010-2020 Google LLC. https://angular.io/
* License: MIT
*/
(function (factory) {
    typeof define === 'function' && define.amd ? define(factory) :
        factory();
}((function () {
    'use strict';
    /**
     * @license
     * Copyright Google LLC All Rights Reserved.
     *
     * Use of this source code is governed by an MIT-style license that can be
     * found in the LICENSE file at https://angular.io/license
     */
    Zone.__load_patch('notification', function (global, Zone, api) {
        var Notification = global['Notification'];
        if (!Notification || !Notification.prototype) {
            return;
        }
        var desc = Object.getOwnPropertyDescriptor(Notification.prototype, 'onerror');
        if (!desc || !desc.configurable) {
            return;
        }
        api.patchOnProperties(Notification.prototype, null);
    });
})));
