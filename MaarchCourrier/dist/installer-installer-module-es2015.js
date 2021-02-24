(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["installer-installer-module"],{

/***/ "/XJU":
/*!*********************************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/frontend/app/installer/customization/customization.component.html ***!
  \*********************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<div class=\"stepContent\">\r\n    <h2 class=\"stepContentTitle\"><i class=\"fas fa-tools\"></i> {{'lang.customization' | translate}}</h2>\r\n    <div class=\"alert-message alert-message-info\" role=\"alert\" style=\"margin-top: 30px;min-width: 100%;\">\r\n        {{'lang.stepCustomization_desc' | translate}}\r\n    </div>\r\n    <form [formGroup]=\"stepFormGroup\" style=\"display: contents;\">\r\n        <div class=\"col-md-6\">\r\n            <mat-form-field appearance=\"outline\">\r\n                <mat-label>{{'lang.instanceId' | translate}}</mat-label>\r\n                <input matInput formControlName=\"customId\">\r\n                <mat-error>\r\n                    <ng-container *ngIf=\"stepFormGroup.controls['customId'].errors?.customExist\">\r\n                        {{'lang.customAlreadyExist' | translate}}\r\n                    </ng-container>\r\n                    <ng-container *ngIf=\"stepFormGroup.controls['customId'].errors?.pattern\">\r\n                        {{'lang.onlySpecialCharAllowed' | translate:{value1: '\"_\", \"-\"'} }}\r\n                    </ng-container>\r\n                </mat-error>\r\n            </mat-form-field>\r\n            <mat-form-field appearance=\"outline\">\r\n                <mat-label>{{'lang.applicationName' | translate}}</mat-label>\r\n                <input matInput formControlName=\"appName\">\r\n            </mat-form-field>\r\n            <div>{{'lang.loginMsg' | translate}} : </div>\r\n            <textarea style=\"padding-top: 10px;\" name=\"loginMessage\" id=\"loginMessage\"\r\n                formControlName=\"loginMessage\"></textarea>\r\n            <br />\r\n            <br />\r\n            <div>{{'lang.homeMsg' | translate}} : </div>\r\n            <textarea style=\"padding-top: 10px;\" name=\"homeMessage\" id=\"homeMessage\"\r\n                formControlName=\"homeMessage\"></textarea>\r\n            <br />\r\n            <br />\r\n        </div>\r\n        <div class=\"col-md-6\">\r\n            <div>{{'lang.chooseLogo' | translate}} : </div>\r\n            <div>\r\n                <mat-card style=\"width: 350px;background-size: 100%;cursor: pointer;\" matRipple>\r\n                    <img [src]=\"logoURL()\" (click)=\"clickLogoButton(uploadLogo)\" style=\"width: 100%;\" />\r\n                    <input type=\"file\" name=\"files[]\" #uploadLogo (change)=\"uploadTrigger($event, 'logo')\"\r\n                        accept=\"image/svg+xml\" style=\"display: none;\">\r\n                </mat-card>\r\n            </div>\r\n            <br />\r\n            <div>{{'lang.chooseLoginBg' | translate}} : </div>\r\n            <div class=\"backgroundList\">\r\n                <mat-card (click)=\"selectBg('assets/bodylogin.jpg')\" style=\"opacity: 0.3;\" class=\"backgroundItem\"\r\n                    [class.disabled]=\"stepFormGroup.controls['bodyLoginBackground'].disabled\"\r\n                    [class.selected]=\"stepFormGroup.controls['bodyLoginBackground'].value === 'assets/bodylogin.jpg'\"\r\n                    style=\"background:url(assets/bodylogin.jpg);background-size: cover;\">\r\n                </mat-card>\r\n                <mat-card *ngFor=\"let background of backgroundList\"\r\n                    (click)=\"selectBg(background.url)\"\r\n                    style=\"opacity: 0.3;\" class=\"backgroundItem\"\r\n                    [class.selected]=\"background.url === stepFormGroup.controls['bodyLoginBackground'].value\"\r\n                    [class.disabled]=\"stepFormGroup.controls['bodyLoginBackground'].disabled\"\r\n                    [style.background]=\"'url('+background.url+')'\">\r\n                </mat-card>\r\n                <mat-card *ngIf=\"!stepFormGroup.controls['bodyLoginBackground'].disabled\"\r\n                    style=\"opacity: 0.3;display: flex;align-items: center;justify-content: center;\"\r\n                    class=\"backgroundItem\" (click)=\"uploadFile.click()\">\r\n                    <input type=\"file\" name=\"files[]\" #uploadFile (change)=\"uploadTrigger($event, 'bg')\"\r\n                        accept=\"image/jpeg\" style=\"display: none;\">\r\n                    <i class=\"fa fa-plus\" style=\"font-size: 30px;color: #666;\"></i>\r\n                </mat-card>\r\n            </div>\r\n        </div>\r\n    </form>\r\n</div>");

/***/ }),

/***/ "0Al4":
/*!*********************************************************************!*\
  !*** ./src/frontend/app/installer/database/database.component.scss ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (".stepContent {\n  margin: auto;\n}\n\n.stepContent .stepContentTitle {\n  color: #135f7f;\n  margin-bottom: 30px;\n  border-bottom: solid 1px;\n  padding: 0;\n}\n\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9mcm9udGVuZC9hcHAvaW5zdGFsbGVyL2RhdGFiYXNlL2RhdGFiYXNlLmNvbXBvbmVudC5zY3NzIiwic3JjL2Zyb250ZW5kL2Nzcy92YXJzLnNjc3MiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBRUE7RUFFSSxZQUFZO0FBRmhCOztBQUFBO0VBSVEsY0NMUztFRE1ULG1CQUFtQjtFQUNuQix3QkFBd0I7RUFDeEIsVUFBVTtBQUFsQiIsImZpbGUiOiJzcmMvZnJvbnRlbmQvYXBwL2luc3RhbGxlci9kYXRhYmFzZS9kYXRhYmFzZS5jb21wb25lbnQuc2NzcyIsInNvdXJjZXNDb250ZW50IjpbIkBpbXBvcnQgJy4uLy4uLy4uL2Nzcy92YXJzLnNjc3MnO1xyXG5cclxuLnN0ZXBDb250ZW50IHtcclxuICAgIC8vIG1heC13aWR0aDogODUwcHg7XHJcbiAgICBtYXJnaW46IGF1dG87XHJcbiAgICAuc3RlcENvbnRlbnRUaXRsZSB7XHJcbiAgICAgICAgY29sb3I6ICRwcmltYXJ5O1xyXG4gICAgICAgIG1hcmdpbi1ib3R0b206IDMwcHg7XHJcbiAgICAgICAgYm9yZGVyLWJvdHRvbTogc29saWQgMXB4O1xyXG4gICAgICAgIHBhZGRpbmc6IDA7XHJcbiAgICB9XHJcbn1cclxuIiwiJGNvbG9yLW1haW46ICM0RjRGNEY7IC8vIGRlZmF1bHQgY29sb3IgaW4gYXBwbGljYXRpb25cclxuJHByaW1hcnk6ICMxMzVmN2Y7IC8vIG1haW4gY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb25cclxuJHNlY29uZGFyeTogI0Y5OTgzMDsgLy8gbWFpbiBjb2xvciB0aGVtZSBvZiBhcHBsaWNhdGlvblxyXG4kYWNjZW50OiAjMDA2ODQxOyAvLyBhY2NlbnQgY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb24gKGxpa2Ugc3VjY2VzcyBidXR0b25zKVxyXG4kd2FybjogIzhlM2U1MjsgLy8gd2FybmluZyBjb2xvciB0aGVtZSBvZiBhcHBsaWNhdGlvblxyXG5cclxuLy8gV0FSTklORyAhIFlPVSBNVVNUIFJFQ09NUElMQVRFIG1hYXJjaC1tYXRlcmlhbC5zY3NzIElGIFZBTFVFUyBDSEFOR0VTIl19 */");

/***/ }),

/***/ "34LY":
/*!***********************************************************************!*\
  !*** ./src/frontend/app/installer/mailserver/mailserver.component.ts ***!
  \***********************************************************************/
/*! exports provided: MailserverComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "MailserverComponent", function() { return MailserverComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _raw_loader_mailserver_component_html__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! raw-loader!./mailserver.component.html */ "9uZR");
/* harmony import */ var _mailserver_component_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./mailserver.component.scss */ "nNBD");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/core */ "fXoL");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/forms */ "3Pt+");
/* harmony import */ var _ngx_translate_core__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @ngx-translate/core */ "sYmb");
/* harmony import */ var _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @service/notification/notification.service */ "AXEc");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! rxjs/operators */ "kU1M");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @angular/common/http */ "tk/3");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! rxjs */ "qCKp");
/* harmony import */ var _installer_service__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../installer.service */ "S2qH");











let MailserverComponent = class MailserverComponent {
    constructor(translate, _formBuilder, notify, http, installerService) {
        this.translate = translate;
        this._formBuilder = _formBuilder;
        this.notify = notify;
        this.http = http;
        this.installerService = installerService;
        this.hidePassword = true;
        this.recipientTest = '';
        this.emailSendLoading = false;
        this.emailSendResult = {
            msg: '',
            debug: ''
        };
        this.smtpTypeList = [
            {
                id: 'smtp',
                label: this.translate.instant('lang.smtpclient')
            },
            {
                id: 'sendmail',
                label: this.translate.instant('lang.smtprelay')
            },
            {
                id: 'qmail',
                label: this.translate.instant('lang.qmail')
            },
            {
                id: 'mail',
                label: this.translate.instant('lang.phpmail')
            }
        ];
        this.smtpSecList = [
            {
                id: '',
                label: this.translate.instant('lang.none')
            },
            {
                id: 'ssl',
                label: 'ssl'
            },
            {
                id: 'tls',
                label: 'tls'
            }
        ];
        const valEmail = [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].pattern(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/), _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required];
        this.stepFormGroup = this._formBuilder.group({
            firstCtrl: ['success', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            type: ['smtp', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            host: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            auth: [true],
            user: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            password: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            secure: ['ssl', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            port: ['465', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            charset: ['utf-8', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            from: ['', valEmail],
        });
    }
    ngOnInit() {
        this.stepFormGroup.valueChanges.subscribe(() => {
            if (this.checkMailserverContent.opened) {
                this.checkMailserverContent.close();
                this.emailSendLoading = false;
                this.emailSendResult = {
                    icon: 'fa-paper-plane primary',
                    msg: this.translate.instant('lang.emailSendInProgress'),
                    debug: ''
                };
            }
        });
        this.stepFormGroup.controls['type'].valueChanges.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_7__["tap"])((data) => {
            if (['smtp', 'mail'].indexOf(data) === -1) {
                this.stepFormGroup.controls['secure'].disable();
                this.stepFormGroup.controls['host'].disable();
                this.stepFormGroup.controls['port'].disable();
                this.stepFormGroup.controls['auth'].disable();
                this.stepFormGroup.controls['user'].disable();
                this.stepFormGroup.controls['password'].disable();
            }
            else {
                this.stepFormGroup.controls['secure'].enable();
                this.stepFormGroup.controls['host'].enable();
                this.stepFormGroup.controls['port'].enable();
                this.stepFormGroup.controls['auth'].enable();
                if (this.stepFormGroup.controls['auth'].value) {
                    this.stepFormGroup.controls['user'].enable();
                    this.stepFormGroup.controls['password'].enable();
                }
            }
        })).subscribe();
        this.stepFormGroup.controls['auth'].valueChanges.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_7__["tap"])((data) => {
            if (!data) {
                this.stepFormGroup.controls['user'].disable();
                this.stepFormGroup.controls['password'].disable();
            }
            else {
                this.stepFormGroup.controls['user'].enable();
                this.stepFormGroup.controls['password'].enable();
            }
        })).subscribe();
    }
    testEmailSend() {
        this.emailSendResult = {
            icon: 'fa-paper-plane primary',
            msg: this.translate.instant('lang.emailSendInProgress'),
            debug: ''
        };
        const email = {
            'sender': { 'email': this.stepFormGroup.controls['from'] },
            'recipients': [this.recipientTest],
            'object': '[' + this.translate.instant('lang.doNotReply') + '] ' + this.translate.instant('lang.emailSendTest'),
            'status': 'EXPRESS',
            'body': this.translate.instant('lang.emailSendTest'),
            'isHtml': false
        };
        this.emailSendLoading = true;
        this.http.get(`../rest/emails`).pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_7__["tap"])((data) => {
            this.emailSendLoading = false;
            this.emailSendResult = {
                icon: 'fa-check green',
                msg: this.translate.instant('lang.emailSendSuccess'),
                debug: ''
            };
        }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_7__["catchError"])((err) => {
            console.log(err);
            this.emailSendLoading = false;
            this.emailSendResult = {
                icon: 'fa-times red',
                msg: this.translate.instant('lang.emailSendFailed'),
                debug: err.error.errors
            };
            return Object(rxjs__WEBPACK_IMPORTED_MODULE_9__["of"])(false);
        })).subscribe();
    }
    initStep() {
        if (this.installerService.isStepAlreadyLaunched('mailserver')) {
            this.stepFormGroup.disable();
        }
    }
    isValidStep() {
        return this.stepFormGroup === undefined ? false : this.stepFormGroup.controls['firstCtrl'].valid;
    }
    getFormGroup() {
        return this.stepFormGroup;
    }
    checkStep() {
        return this.stepFormGroup.valid;
    }
    getInfoToInstall() {
        return [];
        /* return [{
            idStep : 'mailserver',
            body: {
                smtp: this.stepFormGroup.controls['smtp'].value,
                auth: this.stepFormGroup.controls['auth'].value,
                user: this.stepFormGroup.controls['user'].value,
                password: this.stepFormGroup.controls['password'].value,
                secure: this.stepFormGroup.controls['secure'].value,
                port: this.stepFormGroup.controls['port'].value,
                charset: this.stepFormGroup.controls['charset'].value,
                from: this.stepFormGroup.controls['from'].value
            },
            route: '../rest/installer/mailserver',
            description: this.translate.instant('lang.stepMailServerActionDesc'),
            installPriority: 3
        }];*/
    }
};
MailserverComponent.ctorParameters = () => [
    { type: _ngx_translate_core__WEBPACK_IMPORTED_MODULE_5__["TranslateService"] },
    { type: _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormBuilder"] },
    { type: _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_6__["NotificationService"] },
    { type: _angular_common_http__WEBPACK_IMPORTED_MODULE_8__["HttpClient"] },
    { type: _installer_service__WEBPACK_IMPORTED_MODULE_10__["InstallerService"] }
];
MailserverComponent.propDecorators = {
    checkMailserverContent: [{ type: _angular_core__WEBPACK_IMPORTED_MODULE_3__["ViewChild"], args: ['checkMailserverContent', { static: true },] }]
};
MailserverComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_3__["Component"])({
        selector: 'app-mailserver',
        template: _raw_loader_mailserver_component_html__WEBPACK_IMPORTED_MODULE_1__["default"],
        styles: [_mailserver_component_scss__WEBPACK_IMPORTED_MODULE_2__["default"]]
    })
], MailserverComponent);



/***/ }),

/***/ "3qZk":
/*!*******************************************************************!*\
  !*** ./src/frontend/app/installer/welcome/welcome.component.scss ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (".stepContent {\n  margin: auto;\n}\n\n.stepContent .stepContentTitle {\n  color: #135f7f;\n  margin-bottom: 30px;\n  border-bottom: solid 1px;\n  padding: 0;\n}\n\n.stepContent .maarchLogoFull {\n  width: 300px;\n  height: 100px;\n}\n\n.stepContent .mat-divider {\n  margin-top: 10px;\n  margin-bottom: 10px;\n}\n\n.link {\n  text-decoration: underline;\n  color: #135f7f !important;\n}\n\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9mcm9udGVuZC9hcHAvaW5zdGFsbGVyL3dlbGNvbWUvd2VsY29tZS5jb21wb25lbnQuc2NzcyIsInNyYy9mcm9udGVuZC9jc3MvdmFycy5zY3NzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUVBO0VBRUksWUFBWTtBQUZoQjs7QUFBQTtFQUtRLGNDTlM7RURPVCxtQkFBbUI7RUFDbkIsd0JBQXdCO0VBQ3hCLFVBQVU7QUFEbEI7O0FBUEE7RUFZUSxZQUFZO0VBQ1osYUFBYTtBQURyQjs7QUFaQTtFQWlCUSxnQkFBZ0I7RUFDaEIsbUJBQW1CO0FBRDNCOztBQUtBO0VBQ0ksMEJBQTBCO0VBQzFCLHlCQUEwQjtBQUY5QiIsImZpbGUiOiJzcmMvZnJvbnRlbmQvYXBwL2luc3RhbGxlci93ZWxjb21lL3dlbGNvbWUuY29tcG9uZW50LnNjc3MiLCJzb3VyY2VzQ29udGVudCI6WyJAaW1wb3J0ICcuLi8uLi8uLi9jc3MvdmFycy5zY3NzJztcclxuXHJcbi5zdGVwQ29udGVudCB7XHJcbiAgICAvL21heC13aWR0aDogODUwcHg7XHJcbiAgICBtYXJnaW46IGF1dG87XHJcblxyXG4gICAgLnN0ZXBDb250ZW50VGl0bGUge1xyXG4gICAgICAgIGNvbG9yOiAkcHJpbWFyeTtcclxuICAgICAgICBtYXJnaW4tYm90dG9tOiAzMHB4O1xyXG4gICAgICAgIGJvcmRlci1ib3R0b206IHNvbGlkIDFweDtcclxuICAgICAgICBwYWRkaW5nOiAwO1xyXG4gICAgfVxyXG4gICAgXHJcbiAgICAubWFhcmNoTG9nb0Z1bGx7XHJcbiAgICAgICAgd2lkdGg6IDMwMHB4O1xyXG4gICAgICAgIGhlaWdodDogMTAwcHg7XHJcbiAgICB9XHJcbiAgICBcclxuICAgIC5tYXQtZGl2aWRlciB7XHJcbiAgICAgICAgbWFyZ2luLXRvcDogMTBweDtcclxuICAgICAgICBtYXJnaW4tYm90dG9tOiAxMHB4O1xyXG4gICAgfVxyXG59XHJcblxyXG4ubGluayB7XHJcbiAgICB0ZXh0LWRlY29yYXRpb246IHVuZGVybGluZTtcclxuICAgIGNvbG9yOiAkcHJpbWFyeSAhaW1wb3J0YW50O1xyXG59IiwiJGNvbG9yLW1haW46ICM0RjRGNEY7IC8vIGRlZmF1bHQgY29sb3IgaW4gYXBwbGljYXRpb25cclxuJHByaW1hcnk6ICMxMzVmN2Y7IC8vIG1haW4gY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb25cclxuJHNlY29uZGFyeTogI0Y5OTgzMDsgLy8gbWFpbiBjb2xvciB0aGVtZSBvZiBhcHBsaWNhdGlvblxyXG4kYWNjZW50OiAjMDA2ODQxOyAvLyBhY2NlbnQgY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb24gKGxpa2Ugc3VjY2VzcyBidXR0b25zKVxyXG4kd2FybjogIzhlM2U1MjsgLy8gd2FybmluZyBjb2xvciB0aGVtZSBvZiBhcHBsaWNhdGlvblxyXG5cclxuLy8gV0FSTklORyAhIFlPVSBNVVNUIFJFQ09NUElMQVRFIG1hYXJjaC1tYXRlcmlhbC5zY3NzIElGIFZBTFVFUyBDSEFOR0VTIl19 */");

/***/ }),

/***/ "6S62":
/*!********************************************************!*\
  !*** ./src/frontend/app/installer/installer.module.ts ***!
  \********************************************************/
/*! exports provided: InstallerModule */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "InstallerModule", function() { return InstallerModule; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "fXoL");
/* harmony import */ var _app_common_module__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../app-common.module */ "vWc3");
/* harmony import */ var _service_translate_internationalization_module__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @service/translate/internationalization.module */ "cMWS");
/* harmony import */ var _installer_component__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./installer.component */ "M6B2");
/* harmony import */ var _install_action_install_action_component__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./install-action/install-action.component */ "KNaP");
/* harmony import */ var _welcome_welcome_component__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./welcome/welcome.component */ "AHpz");
/* harmony import */ var _prerequisite_prerequisite_component__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./prerequisite/prerequisite.component */ "YKdi");
/* harmony import */ var _database_database_component__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./database/database.component */ "6mgl");
/* harmony import */ var _docservers_docservers_component__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./docservers/docservers.component */ "7gxL");
/* harmony import */ var _customization_customization_component__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./customization/customization.component */ "CImi");
/* harmony import */ var _useradmin_useradmin_component__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./useradmin/useradmin.component */ "OgGL");
/* harmony import */ var _mailserver_mailserver_component__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./mailserver/mailserver.component */ "34LY");
/* harmony import */ var _installer_routing_module__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./installer-routing.module */ "QCVa");
/* harmony import */ var _installer_service__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./installer.service */ "S2qH");
/* harmony import */ var _ngx_translate_core__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! @ngx-translate/core */ "sYmb");
















let InstallerModule = class InstallerModule {
    constructor(translate) {
        translate.setDefaultLang('fr');
    }
};
InstallerModule.ctorParameters = () => [
    { type: _ngx_translate_core__WEBPACK_IMPORTED_MODULE_15__["TranslateService"] }
];
InstallerModule = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["NgModule"])({
        imports: [
            _app_common_module__WEBPACK_IMPORTED_MODULE_2__["SharedModule"],
            _service_translate_internationalization_module__WEBPACK_IMPORTED_MODULE_3__["InternationalizationModule"],
            _installer_routing_module__WEBPACK_IMPORTED_MODULE_13__["InstallerRoutingModule"]
        ],
        declarations: [
            _install_action_install_action_component__WEBPACK_IMPORTED_MODULE_5__["InstallActionComponent"],
            _installer_component__WEBPACK_IMPORTED_MODULE_4__["InstallerComponent"],
            _welcome_welcome_component__WEBPACK_IMPORTED_MODULE_6__["WelcomeComponent"],
            _prerequisite_prerequisite_component__WEBPACK_IMPORTED_MODULE_7__["PrerequisiteComponent"],
            _database_database_component__WEBPACK_IMPORTED_MODULE_8__["DatabaseComponent"],
            _docservers_docservers_component__WEBPACK_IMPORTED_MODULE_9__["DocserversComponent"],
            _customization_customization_component__WEBPACK_IMPORTED_MODULE_10__["CustomizationComponent"],
            _useradmin_useradmin_component__WEBPACK_IMPORTED_MODULE_11__["UseradminComponent"],
            _mailserver_mailserver_component__WEBPACK_IMPORTED_MODULE_12__["MailserverComponent"]
        ],
        entryComponents: [_install_action_install_action_component__WEBPACK_IMPORTED_MODULE_5__["InstallActionComponent"]],
        providers: [_installer_service__WEBPACK_IMPORTED_MODULE_14__["InstallerService"]]
    })
], InstallerModule);



/***/ }),

/***/ "6mgl":
/*!*******************************************************************!*\
  !*** ./src/frontend/app/installer/database/database.component.ts ***!
  \*******************************************************************/
/*! exports provided: DatabaseComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DatabaseComponent", function() { return DatabaseComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _raw_loader_database_component_html__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! raw-loader!./database.component.html */ "iOzh");
/* harmony import */ var _database_component_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./database.component.scss */ "0Al4");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/core */ "fXoL");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/forms */ "3Pt+");
/* harmony import */ var _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @service/notification/notification.service */ "AXEc");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @angular/common/http */ "tk/3");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! rxjs */ "qCKp");
/* harmony import */ var _ngx_translate_core__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @ngx-translate/core */ "sYmb");
/* harmony import */ var _service_functions_service__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @service/functions.service */ "rH+9");
/* harmony import */ var _installer_service__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../installer.service */ "S2qH");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! rxjs/operators */ "kU1M");












let DatabaseComponent = class DatabaseComponent {
    constructor(translate, http, _formBuilder, notify, functionsService, installerService) {
        this.translate = translate;
        this.http = http;
        this._formBuilder = _formBuilder;
        this.notify = notify;
        this.functionsService = functionsService;
        this.installerService = installerService;
        this.hide = true;
        this.connectionState = false;
        this.dbExist = false;
        this.dataFiles = [];
        this.nextStep = new _angular_core__WEBPACK_IMPORTED_MODULE_3__["EventEmitter"]();
        const valDbName = [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].pattern(/^[^\;\" \\]+$/), _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required];
        const valLoginDb = [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].pattern(/^[^ ]+$/), _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required];
        this.stepFormGroup = this._formBuilder.group({
            dbHostCtrl: ['localhost', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            dbLoginCtrl: ['', valLoginDb],
            dbPortCtrl: ['5432', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            dbPasswordCtrl: ['', valLoginDb],
            dbNameCtrl: ['', valDbName],
            dbSampleCtrl: ['data_fr', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            stateStep: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]
        });
    }
    ngOnInit() {
        this.stepFormGroup.controls['dbHostCtrl'].valueChanges.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_11__["tap"])(() => this.stepFormGroup.controls['stateStep'].setValue(''))).subscribe();
        this.stepFormGroup.controls['dbLoginCtrl'].valueChanges.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_11__["tap"])(() => this.stepFormGroup.controls['stateStep'].setValue(''))).subscribe();
        this.stepFormGroup.controls['dbPortCtrl'].valueChanges.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_11__["tap"])(() => this.stepFormGroup.controls['stateStep'].setValue(''))).subscribe();
        this.stepFormGroup.controls['dbPasswordCtrl'].valueChanges.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_11__["tap"])(() => this.stepFormGroup.controls['stateStep'].setValue(''))).subscribe();
        this.stepFormGroup.controls['dbNameCtrl'].valueChanges.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_11__["tap"])(() => this.stepFormGroup.controls['stateStep'].setValue(''))).subscribe();
        this.getDataFiles();
    }
    getDataFiles() {
        this.http.get(`../rest/installer/sqlDataFiles`).pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_11__["tap"])((data) => {
            this.dataFiles = data.dataFiles;
        }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_11__["catchError"])((err) => {
            this.notify.handleSoftErrors(err);
            return Object(rxjs__WEBPACK_IMPORTED_MODULE_7__["of"])(false);
        })).subscribe();
    }
    isValidConnection() {
        return false;
    }
    initStep() {
        if (this.installerService.isStepAlreadyLaunched('database')) {
            this.stepFormGroup.disable();
        }
    }
    checkConnection() {
        const info = {
            server: this.stepFormGroup.controls['dbHostCtrl'].value,
            port: this.stepFormGroup.controls['dbPortCtrl'].value,
            user: this.stepFormGroup.controls['dbLoginCtrl'].value,
            password: this.stepFormGroup.controls['dbPasswordCtrl'].value,
            name: this.stepFormGroup.controls['dbNameCtrl'].value
        };
        this.http.get(`../rest/installer/databaseConnection`, { observe: 'response', params: info }).pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_11__["tap"])((data) => {
            this.dbExist = data.status === 200;
            this.notify.success(this.translate.instant('lang.rightInformations'));
            this.stepFormGroup.controls['stateStep'].setValue('success');
            this.nextStep.emit();
        }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_11__["catchError"])((err) => {
            this.dbExist = false;
            if (err.error.errors === 'Given database has tables') {
                this.notify.error(this.translate.instant('lang.dbNotEmpty'));
            }
            else {
                this.notify.error(this.translate.instant('lang.badInformations'));
            }
            this.stepFormGroup.markAllAsTouched();
            this.stepFormGroup.controls['stateStep'].setValue('');
            return Object(rxjs__WEBPACK_IMPORTED_MODULE_7__["of"])(false);
        })).subscribe();
    }
    checkStep() {
        return this.stepFormGroup.valid;
    }
    isValidStep() {
        if (this.installerService.isStepAlreadyLaunched('database')) {
            return true;
        }
        else {
            return this.stepFormGroup === undefined ? false : this.stepFormGroup.valid;
        }
    }
    isEmptyConnInfo() {
        return this.stepFormGroup.controls['dbHostCtrl'].invalid ||
            this.stepFormGroup.controls['dbPortCtrl'].invalid ||
            this.stepFormGroup.controls['dbLoginCtrl'].invalid ||
            this.stepFormGroup.controls['dbPasswordCtrl'].invalid ||
            this.stepFormGroup.controls['dbNameCtrl'].invalid;
    }
    getFormGroup() {
        return this.installerService.isStepAlreadyLaunched('database') ? true : this.stepFormGroup;
    }
    getInfoToInstall() {
        return [{
                idStep: 'database',
                body: {
                    server: this.stepFormGroup.controls['dbHostCtrl'].value,
                    port: this.stepFormGroup.controls['dbPortCtrl'].value,
                    user: this.stepFormGroup.controls['dbLoginCtrl'].value,
                    password: this.stepFormGroup.controls['dbPasswordCtrl'].value,
                    name: this.stepFormGroup.controls['dbNameCtrl'].value,
                    data: this.stepFormGroup.controls['dbSampleCtrl'].value
                },
                route: {
                    method: 'POST',
                    url: '../rest/installer/database'
                },
                description: this.translate.instant('lang.stepDatabaseActionDesc'),
                installPriority: 2
            }];
    }
};
DatabaseComponent.ctorParameters = () => [
    { type: _ngx_translate_core__WEBPACK_IMPORTED_MODULE_8__["TranslateService"] },
    { type: _angular_common_http__WEBPACK_IMPORTED_MODULE_6__["HttpClient"] },
    { type: _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormBuilder"] },
    { type: _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_5__["NotificationService"] },
    { type: _service_functions_service__WEBPACK_IMPORTED_MODULE_9__["FunctionsService"] },
    { type: _installer_service__WEBPACK_IMPORTED_MODULE_10__["InstallerService"] }
];
DatabaseComponent.propDecorators = {
    nextStep: [{ type: _angular_core__WEBPACK_IMPORTED_MODULE_3__["Output"] }]
};
DatabaseComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_3__["Component"])({
        selector: 'app-database',
        template: _raw_loader_database_component_html__WEBPACK_IMPORTED_MODULE_1__["default"],
        styles: [_database_component_scss__WEBPACK_IMPORTED_MODULE_2__["default"]]
    })
], DatabaseComponent);



/***/ }),

/***/ "7gxL":
/*!***********************************************************************!*\
  !*** ./src/frontend/app/installer/docservers/docservers.component.ts ***!
  \***********************************************************************/
/*! exports provided: DocserversComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DocserversComponent", function() { return DocserversComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _raw_loader_docservers_component_html__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! raw-loader!./docservers.component.html */ "sqCo");
/* harmony import */ var _docservers_component_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./docservers.component.scss */ "WGJY");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/core */ "fXoL");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/forms */ "3Pt+");
/* harmony import */ var _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @service/notification/notification.service */ "AXEc");
/* harmony import */ var _ngx_translate_core__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @ngx-translate/core */ "sYmb");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @angular/common/http */ "tk/3");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! rxjs */ "qCKp");
/* harmony import */ var _installer_service__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../installer.service */ "S2qH");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! rxjs/operators */ "kU1M");











let DocserversComponent = class DocserversComponent {
    constructor(translate, _formBuilder, notify, http, installerService) {
        this.translate = translate;
        this._formBuilder = _formBuilder;
        this.notify = notify;
        this.http = http;
        this.installerService = installerService;
        this.nextStep = new _angular_core__WEBPACK_IMPORTED_MODULE_3__["EventEmitter"]();
        const valPath = [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].pattern(/^[^\'\<\>\|\*\:\?]+$/), _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required];
        this.stepFormGroup = this._formBuilder.group({
            docserversPath: ['/opt/maarch/docservers/', valPath],
            stateStep: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
        });
        this.stepFormGroup.controls['docserversPath'].valueChanges.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["tap"])(() => this.stepFormGroup.controls['stateStep'].setValue(''))).subscribe();
    }
    ngOnInit() {
    }
    isValidStep() {
        if (this.installerService.isStepAlreadyLaunched('docserver')) {
            return true;
        }
        else {
            return this.stepFormGroup === undefined ? false : this.stepFormGroup.valid;
        }
    }
    initStep() {
        if (this.installerService.isStepAlreadyLaunched('docserver')) {
            this.stepFormGroup.disable();
        }
    }
    getFormGroup() {
        return this.installerService.isStepAlreadyLaunched('docserver') ? true : this.stepFormGroup;
    }
    checkAvailability() {
        const info = {
            path: this.stepFormGroup.controls['docserversPath'].value,
        };
        this.http.get(`../rest/installer/docservers`, { params: info }).pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["tap"])((data) => {
            this.notify.success(this.translate.instant('lang.rightInformations'));
            this.stepFormGroup.controls['stateStep'].setValue('success');
            this.nextStep.emit();
        }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["catchError"])((err) => {
            this.notify.error(this.translate.instant('lang.pathUnreacheable'));
            this.stepFormGroup.controls['stateStep'].setValue('');
            return Object(rxjs__WEBPACK_IMPORTED_MODULE_8__["of"])(false);
        })).subscribe();
    }
    getInfoToInstall() {
        return [{
                idStep: 'docserver',
                body: {
                    path: this.stepFormGroup.controls['docserversPath'].value,
                },
                route: {
                    method: 'POST',
                    url: '../rest/installer/docservers'
                },
                description: this.translate.instant('lang.stepDocserversActionDesc'),
                installPriority: 3
            }];
    }
};
DocserversComponent.ctorParameters = () => [
    { type: _ngx_translate_core__WEBPACK_IMPORTED_MODULE_6__["TranslateService"] },
    { type: _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormBuilder"] },
    { type: _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_5__["NotificationService"] },
    { type: _angular_common_http__WEBPACK_IMPORTED_MODULE_7__["HttpClient"] },
    { type: _installer_service__WEBPACK_IMPORTED_MODULE_9__["InstallerService"] }
];
DocserversComponent.propDecorators = {
    nextStep: [{ type: _angular_core__WEBPACK_IMPORTED_MODULE_3__["Output"] }]
};
DocserversComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_3__["Component"])({
        selector: 'app-docservers',
        template: _raw_loader_docservers_component_html__WEBPACK_IMPORTED_MODULE_1__["default"],
        styles: [_docservers_component_scss__WEBPACK_IMPORTED_MODULE_2__["default"]]
    })
], DocserversComponent);



/***/ }),

/***/ "9uZR":
/*!***************************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/frontend/app/installer/mailserver/mailserver.component.html ***!
  \***************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<div class=\"stepContent\">\r\n    <h2 class=\"stepContentTitle\"><i class=\"fa fa-mail-bulk\"></i> {{'lang.stepMailServer' | translate}}</h2>\r\n    <div class=\"alert-message alert-message-info\" role=\"alert\" style=\"margin-top: 30px;min-width: 100%;\"\r\n        [innerHTML]=\"'lang.stepMailServer_desc' | translate\">\r\n    </div>\r\n    <div class=\"alert-message alert-message-danger\" role=\"alert\" style=\"margin-top: 30px;min-width: 100%;\"\r\n    [innerHTML]=\"'lang.stepMailServer_warning' | translate\">\r\n</div>\r\n    <mat-drawer-container autosize>\r\n        <mat-drawer-content>\r\n            <div style=\"width: 99%;\">\r\n                <form [formGroup]=\"stepFormGroup\" style=\"display: contents;\">\r\n                    <mat-form-field>\r\n                        <mat-select #smtpType name=\"smtpType\" [placeholder]=\"'lang.configurationType' | translate\"\r\n                            formControlName=\"type\" required>\r\n                            <mat-option *ngFor=\"let type of smtpTypeList\" [value]=\"type.id\">\r\n                                {{type.label}}\r\n                            </mat-option>\r\n                        </mat-select>\r\n                    </mat-form-field>\r\n                    <div class=\"row\" style=\"margin:0px;\">\r\n                        <div class=\"col-md-2\">\r\n                            <mat-form-field>\r\n                                <mat-select name=\"SMTPSecure\" [placeholder]=\"'lang.smtpAuth' | translate\" formControlName=\"secure\">\r\n                                    <mat-option *ngFor=\"let security of smtpSecList\" [value]=\"security.id\">\r\n                                        {{security.label}}\r\n                                    </mat-option>\r\n                                </mat-select>\r\n                            </mat-form-field>\r\n                        </div>\r\n                        <div class=\"col-md-9\">\r\n                            <mat-form-field>\r\n                                <input matInput name=\"host\" formControlName=\"host\" [placeholder]=\"'lang.host' | translate\" required>\r\n                            </mat-form-field>\r\n                        </div>\r\n                        <div class=\"col-md-1\">\r\n                            <mat-form-field>\r\n                                <input name=\"port\" type=\"number\" matInput formControlName=\"port\"\r\n                                    [placeholder]=\"'lang.port' | translate\" required>\r\n                            </mat-form-field>\r\n                        </div>\r\n                    </div>\r\n                    <mat-slide-toggle color=\"primary\" name=\"SMTPAuth\" formControlName=\"auth\">\r\n                        {{'lang.enableAuth' | translate}}\r\n                    </mat-slide-toggle>\r\n                    <mat-form-field>\r\n                        <input name=\"user\" formControlName=\"user\" matInput placeholder=\"{{'lang.id' | translate}}\" required>\r\n                    </mat-form-field>\r\n                    <mat-form-field>\r\n                        <input name=\"password\" [type]=\"hidePassword ? 'password' : 'text'\" formControlName=\"password\"\r\n                            matInput [placeholder]=\"'lang.password' | translate\" required>\r\n                        <mat-icon color=\"primary\" style=\"cursor: pointer;\" matSuffix\r\n                            (click)=\"hidePassword = !hidePassword\" class=\"fa fa-2x\"\r\n                            [ngClass]=\"[hidePassword ? 'fa-eye-slash' : 'fa-eye']\"></mat-icon>\r\n                    </mat-form-field>\r\n                    <mat-form-field>\r\n                        <input name=\"mailFrom\" formControlName=\"from\" required matInput [placeholder]=\"'lang.mailFrom' | translate\">\r\n                    </mat-form-field>\r\n                </form>\r\n            </div>\r\n        </mat-drawer-content>\r\n        <mat-drawer #checkMailserverContent mode=\"side\" position=\"end\" style=\"padding:10px;width: 350px;\">\r\n            <mat-nav-list disableRipple=\"true\" style=\"display: flex;flex-direction: column;\">\r\n                <h3 mat-subheader>{{'lang.emailSendTest' | translate}}</h3>\r\n                <mat-form-field>\r\n                    <input name=\"recipientTest\" matInput placeholder=\"{{'lang.mailTo' | translate}}\" [(ngModel)]=\"recipientTest\"\r\n                        [disabled]=\"emailSendLoading\">\r\n                    <mat-icon *ngIf=\"!emailSendLoading\" title=\"{{'lang.beginSendTest' | translate}}\" (click)=\"testEmailSend()\"\r\n                        color=\"primary\" style=\"cursor: pointer;\" matSuffix class=\"fa fa-paper-plane fa-2x\"></mat-icon>\r\n                </mat-form-field>\r\n                <mat-list-item *ngIf=\"emailSendResult.msg != ''\">\r\n                    <mat-icon mat-list-icon class=\"fas {{emailSendResult.icon}} fa-2x\"></mat-icon>\r\n                    <p mat-line> {{emailSendResult.msg}} </p>\r\n                </mat-list-item>\r\n            </mat-nav-list>\r\n            <div class=\"bash\" *ngIf=\"this.emailSendResult.msg === ('lang.emailSendFailed' | translate)\">\r\n                {{this.emailSendResult.debug}}\r\n            </div>\r\n        </mat-drawer>\r\n    </mat-drawer-container>\r\n    <div class=\"text-center\">\r\n        <button mat-raised-button type=\"button\" color=\"primary\" (click)=\"checkMailserverContent.open()\"\r\n            [disabled]=\"!stepFormGroup.valid\">{{'lang.checkSendmail' | translate}}</button>\r\n    </div>\r\n</div>");

/***/ }),

/***/ "AHpz":
/*!*****************************************************************!*\
  !*** ./src/frontend/app/installer/welcome/welcome.component.ts ***!
  \*****************************************************************/
/*! exports provided: WelcomeComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "WelcomeComponent", function() { return WelcomeComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _raw_loader_welcome_component_html__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! raw-loader!./welcome.component.html */ "xFLC");
/* harmony import */ var _welcome_component_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./welcome.component.scss */ "3qZk");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/core */ "fXoL");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/common/http */ "tk/3");
/* harmony import */ var _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @service/notification/notification.service */ "AXEc");
/* harmony import */ var _ngx_translate_core__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @ngx-translate/core */ "sYmb");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @angular/forms */ "3Pt+");
/* harmony import */ var _environments_environment__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../../environments/environment */ "MJ5r");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! rxjs/operators */ "kU1M");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! rxjs */ "qCKp");
/* harmony import */ var _service_auth_service__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @service/auth.service */ "uqn4");












let WelcomeComponent = class WelcomeComponent {
    constructor(translate, http, notify, _formBuilder, authService) {
        this.translate = translate;
        this.http = http;
        this.notify = notify;
        this._formBuilder = _formBuilder;
        this.authService = authService;
        this.langs = [];
        this.appVersion = _environments_environment__WEBPACK_IMPORTED_MODULE_8__["environment"].VERSION.split('.')[0] + '.' + _environments_environment__WEBPACK_IMPORTED_MODULE_8__["environment"].VERSION.split('.')[1];
        this.steps = [
            {
                icon: 'fas fa-check-square',
                desc: 'lang.prerequisiteCheck'
            },
            {
                icon: 'fa fa-database',
                desc: 'lang.databaseCreation'
            },
            {
                icon: 'fa fa-database',
                desc: 'lang.dataSampleCreation'
            },
            {
                icon: 'fa fa-hdd',
                desc: 'lang.docserverCreation'
            },
            {
                icon: 'fas fa-tools',
                desc: 'lang.stepCustomizationActionDesc'
            },
            {
                icon: 'fa fa-user',
                desc: 'lang.adminUserCreation'
            },
        ];
        this.customs = [];
    }
    ngOnInit() {
        this.stepFormGroup = this._formBuilder.group({
            lang: ['fr', _angular_forms__WEBPACK_IMPORTED_MODULE_7__["Validators"].required]
        });
        this.getLang();
        if (!this.authService.noInstall) {
            this.getCustoms();
        }
    }
    getLang() {
        this.http.get('../rest/dev/lang').pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_9__["tap"])((data) => {
            this.langs = Object.keys(data.langs).filter(lang => lang !== 'nl');
        }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_9__["catchError"])((err) => {
            this.notify.handleSoftErrors(err);
            return Object(rxjs__WEBPACK_IMPORTED_MODULE_10__["of"])(false);
        })).subscribe();
    }
    changeLang(id) {
        this.translate.use(id);
    }
    getCustoms() {
        this.http.get('../rest/installer/customs').pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_9__["tap"])((data) => {
            this.customs = data.customs;
        }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_9__["catchError"])((err) => {
            this.notify.handleSoftErrors(err);
            return Object(rxjs__WEBPACK_IMPORTED_MODULE_10__["of"])(false);
        })).subscribe();
    }
    initStep() {
        return false;
    }
    getInfoToInstall() {
        return [];
    }
};
WelcomeComponent.ctorParameters = () => [
    { type: _ngx_translate_core__WEBPACK_IMPORTED_MODULE_6__["TranslateService"] },
    { type: _angular_common_http__WEBPACK_IMPORTED_MODULE_4__["HttpClient"] },
    { type: _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_5__["NotificationService"] },
    { type: _angular_forms__WEBPACK_IMPORTED_MODULE_7__["FormBuilder"] },
    { type: _service_auth_service__WEBPACK_IMPORTED_MODULE_11__["AuthService"] }
];
WelcomeComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_3__["Component"])({
        selector: 'app-welcome',
        template: _raw_loader_welcome_component_html__WEBPACK_IMPORTED_MODULE_1__["default"],
        styles: [_welcome_component_scss__WEBPACK_IMPORTED_MODULE_2__["default"]]
    })
], WelcomeComponent);



/***/ }),

/***/ "CImi":
/*!*****************************************************************************!*\
  !*** ./src/frontend/app/installer/customization/customization.component.ts ***!
  \*****************************************************************************/
/*! exports provided: CustomizationComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "CustomizationComponent", function() { return CustomizationComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _raw_loader_customization_component_html__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! raw-loader!./customization.component.html */ "/XJU");
/* harmony import */ var _customization_component_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./customization.component.scss */ "OZfG");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/core */ "fXoL");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/forms */ "3Pt+");
/* harmony import */ var _ngx_translate_core__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @ngx-translate/core */ "sYmb");
/* harmony import */ var _angular_platform_browser__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @angular/platform-browser */ "jhN1");
/* harmony import */ var _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @service/notification/notification.service */ "AXEc");
/* harmony import */ var _environments_environment__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../../environments/environment */ "MJ5r");
/* harmony import */ var ngx_pipes__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ngx-pipes */ "aEDk");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! rxjs/operators */ "kU1M");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @angular/common/http */ "tk/3");
/* harmony import */ var _installer_service__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../installer.service */ "S2qH");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! rxjs */ "qCKp");














let CustomizationComponent = class CustomizationComponent {
    constructor(translate, _formBuilder, notify, sanitizer, scanPipe, http, installerService) {
        this.translate = translate;
        this._formBuilder = _formBuilder;
        this.notify = notify;
        this.sanitizer = sanitizer;
        this.scanPipe = scanPipe;
        this.http = http;
        this.installerService = installerService;
        this.readonlyState = false;
        this.backgroundList = [];
        const valIdentifier = [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].pattern(/^[a-zA-Z0-9_\-]*$/), _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required];
        this.stepFormGroup = this._formBuilder.group({
            firstCtrl: ['success', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            customId: [null, valIdentifier],
            appName: [`Maarch Courrier ${_environments_environment__WEBPACK_IMPORTED_MODULE_8__["environment"].VERSION.split('.')[0] + '.' + _environments_environment__WEBPACK_IMPORTED_MODULE_8__["environment"].VERSION.split('.')[1]}`, _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            loginMessage: [`<span style="color:#24b0ed"><strong>Découvrez votre application via</strong></span>&nbsp;<a title="le guide de visite" href="https://docs.maarch.org/gitbook/html/MaarchCourrier/${_environments_environment__WEBPACK_IMPORTED_MODULE_8__["environment"].VERSION.split('.')[0] + '.' + _environments_environment__WEBPACK_IMPORTED_MODULE_8__["environment"].VERSION.split('.')[1]}/guu/home.html" target="_blank"><span style="color:#f99830;"><strong>le guide de visite en ligne</strong></span></a>`],
            homeMessage: ['<p>D&eacute;couvrez <strong>Maarch Courrier 20.10</strong> avec <a title="notre guide de visite" href="https://docs.maarch.org/" target="_blank"><span style="color:#f99830;"><strong>notre guide de visite en ligne</strong></span></a>.</p>'],
            bodyLoginBackground: ['assets/bodylogin.jpg'],
            uploadedLogo: ['../rest/images?image=logo'],
        });
        this.backgroundList = Array.from({ length: 16 }).map((_, i) => {
            return {
                filename: `${i + 1}.jpg`,
                url: `assets/${i + 1}.jpg`,
            };
        });
    }
    ngOnInit() {
        this.checkCustomExist();
        this.stepFormGroup.controls['customId'].valueChanges.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["startWith"])(''), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["tap"])(() => {
            this.stepFormGroup.controls['firstCtrl'].setValue('');
        }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["debounceTime"])(500), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["filter"])((value) => value.length > 2), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["filter"])(() => this.stepFormGroup.controls['customId'].errors === null || this.stepFormGroup.controls['customId'].errors.pattern === undefined), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["tap"])(() => {
            this.checkCustomExist();
        })).subscribe();
    }
    initStep() {
        if (this.stepFormGroup.controls['customId'].value === null) {
            this.stepFormGroup.controls['customId'].setValue(this.appDatabase.getInfoToInstall()[0].body.name);
        }
        if (this.installerService.isStepAlreadyLaunched('createCustom') && this.installerService.isStepAlreadyLaunched('customization')) {
            this.stepFormGroup.disable();
            this.readonlyState = true;
            tinymce.remove();
            this.initMce(true);
        }
        else if (this.installerService.isStepAlreadyLaunched('createCustom')) {
            this.stepFormGroup.controls['customId'].disable();
            this.stepFormGroup.controls['appName'].disable();
            this.stepFormGroup.controls['firstCtrl'].disable();
        }
        else if (this.installerService.isStepAlreadyLaunched('customization')) {
            this.stepFormGroup.controls['loginMessage'].disable();
            this.stepFormGroup.controls['homeMessage'].disable();
            this.stepFormGroup.controls['bodyLoginBackground'].disable();
            this.stepFormGroup.controls['uploadedLogo'].disable();
            this.readonlyState = true;
            tinymce.remove();
            this.initMce(true);
        }
        else {
            this.readonlyState = false;
            this.initMce();
        }
    }
    checkCustomExist() {
        this.http.get('../rest/installer/custom', { observe: 'response', params: { 'customId': this.stepFormGroup.controls['customId'].value } }).pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["tap"])((response) => {
            if (this.stepFormGroup.controls['customId'].errors !== null) {
                const error = this.stepFormGroup.controls['customId'].errors;
                delete error.customExist;
            }
            else {
                this.stepFormGroup.controls['firstCtrl'].setValue('success');
            }
        }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["catchError"])((err) => {
            const regex = /^Custom already exists/g;
            if (err.error.errors.match(regex) !== null) {
                this.stepFormGroup.controls['customId'].setErrors(Object.assign(Object.assign({}, this.stepFormGroup.controls['customId'].errors), { customExist: true }));
                this.stepFormGroup.controls['customId'].markAsTouched();
            }
            else {
                this.notify.handleSoftErrors(err);
            }
            return Object(rxjs__WEBPACK_IMPORTED_MODULE_13__["of"])(false);
        })).subscribe();
    }
    isValidStep() {
        if (this.installerService.isStepAlreadyLaunched('createCustom') && this.installerService.isStepAlreadyLaunched('customization')) {
            return true;
        }
        else {
            return this.stepFormGroup === undefined ? false : this.stepFormGroup.valid;
        }
    }
    getFormGroup() {
        return this.installerService.isStepAlreadyLaunched('createCustom') && this.installerService.isStepAlreadyLaunched('customization') ? true : this.stepFormGroup;
    }
    initMce(readonly = false) {
        tinymce.init({
            selector: 'textarea',
            base_url: '../node_modules/tinymce/',
            height: '150',
            suffix: '.min',
            language: this.translate.instant('lang.langISO').replace('-', '_'),
            language_url: `../node_modules/tinymce-i18n/langs/${this.translate.instant('lang.langISO').replace('-', '_')}.js`,
            menubar: false,
            statusbar: false,
            readonly: readonly,
            plugins: [
                'autolink'
            ],
            external_plugins: {
                'maarch_b64image': '../../src/frontend/plugins/tinymce/maarch_b64image/plugin.min.js'
            },
            toolbar_sticky: true,
            toolbar_drawer: 'floating',
            toolbar: !readonly ? 'undo redo | fontselect fontsizeselect | bold italic underline strikethrough forecolor | maarch_b64image | \
        alignleft aligncenter alignright alignjustify \
        bullist numlist outdent indent | removeformat' : ''
        });
    }
    getInfoToInstall() {
        return [
            {
                idStep: 'createCustom',
                body: {
                    lang: this.appWelcome.stepFormGroup.controls['lang'].value,
                    customId: this.stepFormGroup.controls['customId'].value,
                    applicationName: this.stepFormGroup.controls['appName'].value,
                },
                description: this.translate.instant('lang.stepInstanceActionDesc'),
                route: {
                    method: 'POST',
                    url: '../rest/installer/custom'
                },
                installPriority: 1
            },
            {
                idStep: 'customization',
                body: {
                    loginMessage: tinymce.get('loginMessage').getContent(),
                    homeMessage: tinymce.get('homeMessage').getContent(),
                    bodyLoginBackground: this.stepFormGroup.controls['bodyLoginBackground'].value,
                    logo: this.stepFormGroup.controls['uploadedLogo'].value,
                },
                description: this.translate.instant('lang.stepCustomizationActionDesc'),
                route: {
                    method: 'POST',
                    url: '../rest/installer/customization'
                },
                installPriority: 3
            }
        ];
    }
    uploadTrigger(fileInput, mode) {
        if (fileInput.target.files && fileInput.target.files[0]) {
            const res = this.canUploadFile(fileInput.target.files[0], mode);
            if (res === true) {
                const reader = new FileReader();
                reader.readAsDataURL(fileInput.target.files[0]);
                reader.onload = (value) => {
                    if (mode === 'logo') {
                        this.stepFormGroup.controls['uploadedLogo'].setValue(value.target.result);
                    }
                    else {
                        const img = new Image();
                        img.onload = (imgDim) => {
                            if (imgDim.target.width < 1920 || imgDim.target.height < 1080) {
                                this.notify.error(this.translate.instant('lang.badImageResolution', { value1: '1920x1080' }));
                            }
                            else {
                                this.backgroundList.push({
                                    filename: value.target.result,
                                    url: value.target.result,
                                });
                                this.stepFormGroup.controls['bodyLoginBackground'].setValue(value.target.result);
                            }
                        };
                        img.src = value.target.result;
                    }
                };
            }
            else {
                this.notify.error(res);
            }
        }
    }
    canUploadFile(file, mode) {
        const allowedExtension = mode !== 'logo' ? ['image/jpg', 'image/jpeg'] : ['image/svg+xml'];
        if (mode === 'logo') {
            if (file.size > 5000000) {
                return this.translate.instant('lang.maxFileSizeExceeded', { value1: '5mo' });
            }
            else if (allowedExtension.indexOf(file.type) === -1) {
                return this.translate.instant('lang.onlyExtensionsAllowed', { value1: allowedExtension.join(', ') });
            }
        }
        else {
            if (file.size > 10000000) {
                return this.translate.instant('lang.maxFileSizeExceeded', { value1: '10mo' });
            }
            else if (allowedExtension.indexOf(file.type) === -1) {
                return this.translate.instant('lang.onlyExtensionsAllowed', { value1: allowedExtension.join(', ') });
            }
        }
        return true;
    }
    logoURL() {
        return this.sanitizer.bypassSecurityTrustUrl(this.stepFormGroup.controls['uploadedLogo'].value);
    }
    selectBg(content) {
        if (!this.stepFormGroup.controls['bodyLoginBackground'].disabled) {
            this.stepFormGroup.controls['bodyLoginBackground'].setValue(content);
        }
    }
    clickLogoButton(uploadLogo) {
        if (!this.stepFormGroup.controls['uploadedLogo'].disabled) {
            uploadLogo.click();
        }
    }
};
CustomizationComponent.ctorParameters = () => [
    { type: _ngx_translate_core__WEBPACK_IMPORTED_MODULE_5__["TranslateService"] },
    { type: _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormBuilder"] },
    { type: _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_7__["NotificationService"] },
    { type: _angular_platform_browser__WEBPACK_IMPORTED_MODULE_6__["DomSanitizer"] },
    { type: ngx_pipes__WEBPACK_IMPORTED_MODULE_9__["ScanPipe"] },
    { type: _angular_common_http__WEBPACK_IMPORTED_MODULE_11__["HttpClient"] },
    { type: _installer_service__WEBPACK_IMPORTED_MODULE_12__["InstallerService"] }
];
CustomizationComponent.propDecorators = {
    appDatabase: [{ type: _angular_core__WEBPACK_IMPORTED_MODULE_3__["Input"] }],
    appWelcome: [{ type: _angular_core__WEBPACK_IMPORTED_MODULE_3__["Input"] }]
};
CustomizationComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_3__["Component"])({
        selector: 'app-customization',
        template: _raw_loader_customization_component_html__WEBPACK_IMPORTED_MODULE_1__["default"],
        providers: [ngx_pipes__WEBPACK_IMPORTED_MODULE_9__["ScanPipe"]],
        styles: [_customization_component_scss__WEBPACK_IMPORTED_MODULE_2__["default"]]
    })
], CustomizationComponent);



/***/ }),

/***/ "FS/S":
/*!*************************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/frontend/app/installer/useradmin/useradmin.component.html ***!
  \*************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<div class=\"stepContent\">\r\n    <h2 class=\"stepContentTitle\"><i class=\"fas fa-user\"></i> {{'lang.userAdmin' | translate}}</h2>\r\n    <div class=\"alert-message alert-message-info\" role=\"alert\" style=\"margin-top: 30px;min-width: 100%;\">\r\n        {{'lang.stepUserAdmin_desc' | translate}}\r\n    </div>\r\n    <form [formGroup]=\"stepFormGroup\" style=\"width: 850px;margin: auto;\">\r\n        <mat-form-field appearance=\"outline\">\r\n            <mat-label>{{'lang.id' | translate}}</mat-label>\r\n            <input matInput formControlName=\"login\">\r\n        </mat-form-field>\r\n        <mat-form-field appearance=\"outline\">\r\n            <mat-label>{{'lang.firstname' | translate}}</mat-label>\r\n            <input matInput formControlName=\"firstname\">\r\n        </mat-form-field>\r\n        <mat-form-field appearance=\"outline\">\r\n            <mat-label>{{'lang.lastname' | translate}}</mat-label>\r\n            <input matInput formControlName=\"lastname\">\r\n        </mat-form-field>\r\n        <mat-form-field appearance=\"outline\">\r\n            <mat-label>{{'lang.password' | translate}}</mat-label>\r\n            <input [type]=\"hide ? 'password' : 'text'\" matInput formControlName=\"password\">\r\n            <button mat-icon-button matSuffix color=\"primary\" (click)=\"hide = !hide\">\r\n                <mat-icon class=\"fa {{hide ? 'fa-eye-slash' : 'fa-eye'}}\"></mat-icon>\r\n            </button>\r\n            <mat-error>{{'lang.passwordNotMatch' | translate}}</mat-error>\r\n        </mat-form-field>\r\n        <mat-form-field appearance=\"outline\">\r\n            <mat-label>{{'lang.retypeNewPassword' | translate}}</mat-label>\r\n            <input [type]=\"hide ? 'password' : 'text'\" matInput formControlName=\"passwordConfirm\">\r\n            <button mat-icon-button matSuffix color=\"primary\" (click)=\"hide = !hide\">\r\n                <mat-icon class=\"fa {{hide ? 'fa-eye-slash' : 'fa-eye'}}\"></mat-icon>\r\n            </button>\r\n            <mat-error>{{'lang.passwordNotMatch' | translate}}</mat-error>\r\n        </mat-form-field>\r\n        <mat-form-field appearance=\"outline\">\r\n            <mat-label>{{'lang.email' | translate}}</mat-label>\r\n            <input matInput formControlName=\"email\">\r\n        </mat-form-field>\r\n    </form>\r\n</div>\r\n");

/***/ }),

/***/ "KNaP":
/*!*******************************************************************************!*\
  !*** ./src/frontend/app/installer/install-action/install-action.component.ts ***!
  \*******************************************************************************/
/*! exports provided: InstallActionComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "InstallActionComponent", function() { return InstallActionComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _raw_loader_install_action_component_html__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! raw-loader!./install-action.component.html */ "e0dC");
/* harmony import */ var _install_action_component_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./install-action.component.scss */ "v/rb");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/core */ "fXoL");
/* harmony import */ var _angular_material_dialog__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/material/dialog */ "0IaG");
/* harmony import */ var _ngx_translate_core__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @ngx-translate/core */ "sYmb");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @angular/common/http */ "tk/3");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! rxjs */ "qCKp");
/* harmony import */ var _installer_service__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../installer.service */ "S2qH");
/* harmony import */ var _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @service/notification/notification.service */ "AXEc");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! rxjs/operators */ "kU1M");
/* harmony import */ var _service_auth_service__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @service/auth.service */ "uqn4");












let InstallActionComponent = class InstallActionComponent {
    constructor(translate, data, dialogRef, http, installerService, notify, authService) {
        this.translate = translate;
        this.data = data;
        this.dialogRef = dialogRef;
        this.http = http;
        this.installerService = installerService;
        this.notify = notify;
        this.authService = authService;
        this.steps = [];
        this.customId = '';
        // Workaround for angular component issue #13870
        this.disableAnimation = true;
    }
    ngOnInit() {
        return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__awaiter"])(this, void 0, void 0, function* () {
            this.initSteps();
        });
    }
    ngAfterViewInit() {
        setTimeout(() => this.disableAnimation = false);
    }
    launchInstall() {
        return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__awaiter"])(this, void 0, void 0, function* () {
            let res;
            for (let index = 0; index < this.data.length; index++) {
                this.steps[index].state = 'inProgress';
                res = yield this.doStep(index);
                if (!res) {
                    break;
                }
            }
        });
    }
    initSteps() {
        this.data.forEach((step, index) => {
            if (index === 0) {
                this.customId = step.body.customId;
            }
            else {
                step.body.customId = this.customId;
            }
            this.steps.push({
                idStep: step.idStep,
                label: step.description,
                state: '',
                msgErr: '',
            });
        });
    }
    doStep(index) {
        return new Promise((resolve, reject) => {
            if (this.installerService.isStepAlreadyLaunched(this.data[index].idStep)) {
                this.steps[index].state = 'OK';
                resolve(true);
            }
            else {
                this.http[this.data[index].route.method.toLowerCase()](this.data[index].route.url, this.data[index].body).pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["tap"])((data) => {
                    this.steps[index].state = 'OK';
                    this.installerService.setStep(this.steps[index]);
                    resolve(true);
                }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["catchError"])((err) => {
                    this.steps[index].state = 'KO';
                    if (err.error.lang !== undefined) {
                        this.steps[index].msgErr = this.translate.instant('lang.' + err.error.lang);
                    }
                    else {
                        this.steps[index].msgErr = err.error.errors;
                    }
                    resolve(false);
                    return Object(rxjs__WEBPACK_IMPORTED_MODULE_7__["of"])(false);
                })).subscribe();
            }
        });
    }
    isInstallBegin() {
        return this.steps.filter(step => step.state === '').length !== this.steps.length;
    }
    isInstallComplete() {
        return this.steps.filter(step => step.state === '').length === 0;
    }
    isInstallError() {
        return this.steps.filter(step => step.state === 'KO').length > 0;
    }
    goToInstance() {
        this.http.request('DELETE', '../rest/installer/lock', { body: { customId: this.customId } }).pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["tap"])((res) => {
            this.authService.noInstall = false;
            window.location.href = res.url;
            this.dialogRef.close('ok');
        }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["catchError"])((err) => {
            this.notify.handleSoftErrors(err);
            return Object(rxjs__WEBPACK_IMPORTED_MODULE_7__["of"])(false);
        })).subscribe();
    }
};
InstallActionComponent.ctorParameters = () => [
    { type: _ngx_translate_core__WEBPACK_IMPORTED_MODULE_5__["TranslateService"] },
    { type: undefined, decorators: [{ type: _angular_core__WEBPACK_IMPORTED_MODULE_3__["Inject"], args: [_angular_material_dialog__WEBPACK_IMPORTED_MODULE_4__["MAT_DIALOG_DATA"],] }] },
    { type: _angular_material_dialog__WEBPACK_IMPORTED_MODULE_4__["MatDialogRef"] },
    { type: _angular_common_http__WEBPACK_IMPORTED_MODULE_6__["HttpClient"] },
    { type: _installer_service__WEBPACK_IMPORTED_MODULE_8__["InstallerService"] },
    { type: _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_9__["NotificationService"] },
    { type: _service_auth_service__WEBPACK_IMPORTED_MODULE_11__["AuthService"] }
];
InstallActionComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_3__["Component"])({
        selector: 'app-install-action',
        template: _raw_loader_install_action_component_html__WEBPACK_IMPORTED_MODULE_1__["default"],
        styles: [_install_action_component_scss__WEBPACK_IMPORTED_MODULE_2__["default"]]
    })
], InstallActionComponent);



/***/ }),

/***/ "M6B2":
/*!***********************************************************!*\
  !*** ./src/frontend/app/installer/installer.component.ts ***!
  \***********************************************************/
/*! exports provided: InstallerComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "InstallerComponent", function() { return InstallerComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _raw_loader_installer_component_html__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! raw-loader!./installer.component.html */ "ozdG");
/* harmony import */ var _installer_component_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./installer.component.scss */ "TE+0");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/core */ "fXoL");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/router */ "tyNb");
/* harmony import */ var _service_header_service__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @service/header.service */ "4zkx");
/* harmony import */ var _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @service/notification/notification.service */ "AXEc");
/* harmony import */ var _angular_cdk_stepper__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @angular/cdk/stepper */ "B/XX");
/* harmony import */ var _service_app_service__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @service/app.service */ "A6w4");
/* harmony import */ var _ngx_translate_core__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @ngx-translate/core */ "sYmb");
/* harmony import */ var _plugins_sorting_pipe__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../plugins/sorting.pipe */ "1YbM");
/* harmony import */ var _angular_material_dialog__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @angular/material/dialog */ "0IaG");
/* harmony import */ var _install_action_install_action_component__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./install-action/install-action.component */ "KNaP");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! rxjs */ "qCKp");
/* harmony import */ var _service_functions_service__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! @service/functions.service */ "rH+9");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! rxjs/operators */ "kU1M");
/* harmony import */ var _service_auth_service__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! @service/auth.service */ "uqn4");
/* harmony import */ var _service_privileges_service__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! @service/privileges.service */ "eiH7");


















let InstallerComponent = class InstallerComponent {
    constructor(translate, router, headerService, notify, appService, sortPipe, dialog, functionService, privilegeService, authService) {
        this.translate = translate;
        this.router = router;
        this.headerService = headerService;
        this.notify = notify;
        this.appService = appService;
        this.sortPipe = sortPipe;
        this.dialog = dialog;
        this.functionService = functionService;
        this.privilegeService = privilegeService;
        this.authService = authService;
        this.loading = true;
    }
    ngOnInit() {
        this.headerService.hideSideBar = true;
        if (!this.authService.isAuth() && !this.authService.noInstall) {
            this.router.navigate(['/login']);
            this.notify.error(this.translate.instant('lang.mustConnectToInstall'));
        }
        else if (this.authService.getToken() !== null && !this.privilegeService.hasCurrentUserPrivilege('create_custom')) {
            this.router.navigate(['/login']);
            this.notify.error(this.translate.instant('lang.mustPrivilegeToInstall'));
        }
        else {
            this.loading = false;
        }
    }
    ngAfterViewInit() {
        $('.mat-horizontal-stepper-header-container').insertBefore('.bg-head-content');
        $('.mat-step-icon').css('background-color', 'white');
        $('.mat-step-icon').css('color', '#135f7f');
        $('.mat-step-label').css('color', 'white');
        /*$('.mat-step-label').css('opacity', '0.5');
        $('.mat-step-label-active').css('opacity', '1');*/
        /*$('.mat-step-label-selected').css('font-size', '160%');
        $('.mat-step-label-selected').css('transition', 'all 0.2s');
        $('.mat-step-label').css('transition', 'all 0.2s');*/
    }
    isValidStep() {
        return false;
    }
    initStep(ev) {
        this.stepContent.toArray()[ev.selectedIndex].initStep();
    }
    nextStep() {
        this.stepper.next();
    }
    gotToLogin() {
        this.router.navigate(['/login']);
    }
    endInstall() {
        let installContent = [];
        this.stepContent.toArray().forEach((component) => {
            installContent = installContent.concat(component.getInfoToInstall());
        });
        installContent = this.sortPipe.transform(installContent, 'installPriority');
        const dialogRef = this.dialog.open(_install_action_install_action_component__WEBPACK_IMPORTED_MODULE_12__["InstallActionComponent"], {
            panelClass: 'maarch-modal',
            disableClose: true,
            width: '500px',
            data: installContent
        });
        dialogRef.afterClosed().pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_15__["filter"])((result) => !this.functionService.empty(result)), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_15__["tap"])((result) => {
        }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_15__["catchError"])((err) => {
            this.notify.handleErrors(err);
            return Object(rxjs__WEBPACK_IMPORTED_MODULE_13__["of"])(false);
        })).subscribe();
    }
};
InstallerComponent.ctorParameters = () => [
    { type: _ngx_translate_core__WEBPACK_IMPORTED_MODULE_9__["TranslateService"] },
    { type: _angular_router__WEBPACK_IMPORTED_MODULE_4__["Router"] },
    { type: _service_header_service__WEBPACK_IMPORTED_MODULE_5__["HeaderService"] },
    { type: _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_6__["NotificationService"] },
    { type: _service_app_service__WEBPACK_IMPORTED_MODULE_8__["AppService"] },
    { type: _plugins_sorting_pipe__WEBPACK_IMPORTED_MODULE_10__["SortPipe"] },
    { type: _angular_material_dialog__WEBPACK_IMPORTED_MODULE_11__["MatDialog"] },
    { type: _service_functions_service__WEBPACK_IMPORTED_MODULE_14__["FunctionsService"] },
    { type: _service_privileges_service__WEBPACK_IMPORTED_MODULE_17__["PrivilegeService"] },
    { type: _service_auth_service__WEBPACK_IMPORTED_MODULE_16__["AuthService"] }
];
InstallerComponent.propDecorators = {
    stepContent: [{ type: _angular_core__WEBPACK_IMPORTED_MODULE_3__["ViewChildren"], args: ['stepContent',] }],
    stepper: [{ type: _angular_core__WEBPACK_IMPORTED_MODULE_3__["ViewChild"], args: ['stepper', { static: false },] }]
};
InstallerComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_3__["Component"])({
        template: _raw_loader_installer_component_html__WEBPACK_IMPORTED_MODULE_1__["default"],
        providers: [
            {
                provide: _angular_cdk_stepper__WEBPACK_IMPORTED_MODULE_7__["STEPPER_GLOBAL_OPTIONS"], useValue: { showError: true },
            },
            _plugins_sorting_pipe__WEBPACK_IMPORTED_MODULE_10__["SortPipe"]
        ],
        styles: [_installer_component_scss__WEBPACK_IMPORTED_MODULE_2__["default"]]
    })
], InstallerComponent);



/***/ }),

/***/ "OZfG":
/*!*******************************************************************************!*\
  !*** ./src/frontend/app/installer/customization/customization.component.scss ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (".stepContent {\n  margin: auto;\n}\n\n.stepContent .stepContentTitle {\n  color: #135f7f;\n  margin-bottom: 30px;\n  border-bottom: solid 1px;\n  padding: 0;\n}\n\n.backgroundList {\n  display: grid;\n  grid-template-columns: repeat(5, 1fr);\n  grid-gap: 10px;\n}\n\n.selected {\n  transition: all 0.3s;\n  opacity: 1 !important;\n  border: solid 10px #F99830 !important;\n}\n\n.backgroundItem {\n  border: solid 0px #F99830;\n  opacity: 0.5;\n  transition: all 0.3s;\n  cursor: pointer;\n  height: 120px;\n  background-size: cover !important;\n}\n\n.disabled {\n  cursor: default !important;\n}\n\n.backgroundItem:not(.disabled):hover {\n  transition: all 0.3s;\n  opacity: 1 !important;\n}\n\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9mcm9udGVuZC9hcHAvaW5zdGFsbGVyL2N1c3RvbWl6YXRpb24vY3VzdG9taXphdGlvbi5jb21wb25lbnQuc2NzcyIsInNyYy9mcm9udGVuZC9jc3MvdmFycy5zY3NzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUdBO0VBRUksWUFBWTtBQUhoQjs7QUFDQTtFQUtRLGNDUFM7RURRVCxtQkFBbUI7RUFDbkIsd0JBQXdCO0VBQ3hCLFVBQVU7QUFGbEI7O0FBTUE7RUFDSSxhQUFhO0VBQ2IscUNBQXFDO0VBQ3JDLGNBQWM7QUFIbEI7O0FBTUE7RUFDSSxvQkFBb0I7RUFDcEIscUJBQXFCO0VBQ3JCLHFDQUF3QztBQUg1Qzs7QUFNQTtFQUNJLHlCQzFCZTtFRDJCZixZQUFZO0VBQ1osb0JBQW9CO0VBQ3BCLGVBQWU7RUFDZixhQUFhO0VBQ2IsaUNBQWlDO0FBSHJDOztBQU1BO0VBQ0ksMEJBQTBCO0FBSDlCOztBQU1BO0VBQ0ksb0JBQW9CO0VBQ3BCLHFCQUFxQjtBQUh6QiIsImZpbGUiOiJzcmMvZnJvbnRlbmQvYXBwL2luc3RhbGxlci9jdXN0b21pemF0aW9uL2N1c3RvbWl6YXRpb24uY29tcG9uZW50LnNjc3MiLCJzb3VyY2VzQ29udGVudCI6WyJAaW1wb3J0ICcuLi8uLi8uLi9jc3MvdmFycy5zY3NzJztcclxuXHJcblxyXG4uc3RlcENvbnRlbnQge1xyXG4gICAgLy8gbWF4LXdpZHRoOiA4NTBweDtcclxuICAgIG1hcmdpbjogYXV0bztcclxuXHJcbiAgICAuc3RlcENvbnRlbnRUaXRsZSB7XHJcbiAgICAgICAgY29sb3I6ICRwcmltYXJ5O1xyXG4gICAgICAgIG1hcmdpbi1ib3R0b206IDMwcHg7XHJcbiAgICAgICAgYm9yZGVyLWJvdHRvbTogc29saWQgMXB4O1xyXG4gICAgICAgIHBhZGRpbmc6IDA7XHJcbiAgICB9XHJcbn1cclxuXHJcbi5iYWNrZ3JvdW5kTGlzdCB7XHJcbiAgICBkaXNwbGF5OiBncmlkO1xyXG4gICAgZ3JpZC10ZW1wbGF0ZS1jb2x1bW5zOiByZXBlYXQoNSwgMWZyKTtcclxuICAgIGdyaWQtZ2FwOiAxMHB4O1xyXG59XHJcblxyXG4uc2VsZWN0ZWQge1xyXG4gICAgdHJhbnNpdGlvbjogYWxsIDAuM3M7XHJcbiAgICBvcGFjaXR5OiAxICFpbXBvcnRhbnQ7XHJcbiAgICBib3JkZXI6IHNvbGlkIDEwcHggJHNlY29uZGFyeSAhaW1wb3J0YW50O1xyXG59XHJcblxyXG4uYmFja2dyb3VuZEl0ZW0ge1xyXG4gICAgYm9yZGVyOiBzb2xpZCAwcHggJHNlY29uZGFyeTtcclxuICAgIG9wYWNpdHk6IDAuNTtcclxuICAgIHRyYW5zaXRpb246IGFsbCAwLjNzO1xyXG4gICAgY3Vyc29yOiBwb2ludGVyO1xyXG4gICAgaGVpZ2h0OiAxMjBweDtcclxuICAgIGJhY2tncm91bmQtc2l6ZTogY292ZXIgIWltcG9ydGFudDtcclxufVxyXG5cclxuLmRpc2FibGVkIHtcclxuICAgIGN1cnNvcjogZGVmYXVsdCAhaW1wb3J0YW50O1xyXG59XHJcblxyXG4uYmFja2dyb3VuZEl0ZW06bm90KC5kaXNhYmxlZCk6aG92ZXIge1xyXG4gICAgdHJhbnNpdGlvbjogYWxsIDAuM3M7XHJcbiAgICBvcGFjaXR5OiAxICFpbXBvcnRhbnQ7XHJcbn0iLCIkY29sb3ItbWFpbjogIzRGNEY0RjsgLy8gZGVmYXVsdCBjb2xvciBpbiBhcHBsaWNhdGlvblxyXG4kcHJpbWFyeTogIzEzNWY3ZjsgLy8gbWFpbiBjb2xvciB0aGVtZSBvZiBhcHBsaWNhdGlvblxyXG4kc2Vjb25kYXJ5OiAjRjk5ODMwOyAvLyBtYWluIGNvbG9yIHRoZW1lIG9mIGFwcGxpY2F0aW9uXHJcbiRhY2NlbnQ6ICMwMDY4NDE7IC8vIGFjY2VudCBjb2xvciB0aGVtZSBvZiBhcHBsaWNhdGlvbiAobGlrZSBzdWNjZXNzIGJ1dHRvbnMpXHJcbiR3YXJuOiAjOGUzZTUyOyAvLyB3YXJuaW5nIGNvbG9yIHRoZW1lIG9mIGFwcGxpY2F0aW9uXHJcblxyXG4vLyBXQVJOSU5HICEgWU9VIE1VU1QgUkVDT01QSUxBVEUgbWFhcmNoLW1hdGVyaWFsLnNjc3MgSUYgVkFMVUVTIENIQU5HRVMiXX0= */");

/***/ }),

/***/ "OgGL":
/*!*********************************************************************!*\
  !*** ./src/frontend/app/installer/useradmin/useradmin.component.ts ***!
  \*********************************************************************/
/*! exports provided: UseradminComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "UseradminComponent", function() { return UseradminComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _raw_loader_useradmin_component_html__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! raw-loader!./useradmin.component.html */ "FS/S");
/* harmony import */ var _useradmin_component_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./useradmin.component.scss */ "idS4");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/core */ "fXoL");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/forms */ "3Pt+");
/* harmony import */ var _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @service/notification/notification.service */ "AXEc");
/* harmony import */ var _ngx_translate_core__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @ngx-translate/core */ "sYmb");
/* harmony import */ var _installer_service__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../installer.service */ "S2qH");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! rxjs/operators */ "kU1M");









let UseradminComponent = class UseradminComponent {
    constructor(translate, _formBuilder, notify, installerService) {
        this.translate = translate;
        this._formBuilder = _formBuilder;
        this.notify = notify;
        this.installerService = installerService;
        this.hide = true;
        this.tiggerInstall = new _angular_core__WEBPACK_IMPORTED_MODULE_3__["EventEmitter"]();
        const valLogin = [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].pattern(/^[\w.@-]*$/), _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required];
        const valEmail = [_angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].pattern(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/), _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required];
        this.stepFormGroup = this._formBuilder.group({
            login: ['superadmin', valLogin],
            firstname: ['Admin', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            lastname: ['SUPER', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            password: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            passwordConfirm: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required],
            email: ['yourEmail@domain.com', valEmail],
        });
    }
    ngOnInit() {
        this.stepFormGroup.controls['password'].valueChanges.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_8__["tap"])((data) => {
            if (data !== this.stepFormGroup.controls['passwordConfirm'].value) {
                this.stepFormGroup.controls['password'].setErrors({ 'incorrect': true });
                this.stepFormGroup.controls['passwordConfirm'].setErrors({ 'incorrect': true });
                this.stepFormGroup.controls['passwordConfirm'].markAsTouched();
            }
            else {
                this.stepFormGroup.controls['password'].setErrors(null);
                this.stepFormGroup.controls['passwordConfirm'].setErrors(null);
            }
        })).subscribe();
        this.stepFormGroup.controls['passwordConfirm'].valueChanges.pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_8__["tap"])((data) => {
            if (data !== this.stepFormGroup.controls['password'].value) {
                this.stepFormGroup.controls['password'].setErrors({ 'incorrect': true });
                this.stepFormGroup.controls['password'].markAsTouched();
                this.stepFormGroup.controls['passwordConfirm'].setErrors({ 'incorrect': true });
            }
            else {
                this.stepFormGroup.controls['password'].setErrors(null);
                this.stepFormGroup.controls['passwordConfirm'].setErrors(null);
            }
        })).subscribe();
    }
    initStep() {
        if (this.installerService.isStepAlreadyLaunched('userAdmin')) {
            this.stepFormGroup.disable();
        }
    }
    isValidStep() {
        return this.stepFormGroup === undefined ? false : this.stepFormGroup.valid || this.installerService.isStepAlreadyLaunched('userAdmin');
    }
    getFormGroup() {
        return this.installerService.isStepAlreadyLaunched('userAdmin') ? true : this.stepFormGroup;
    }
    getInfoToInstall() {
        return [{
                idStep: 'userAdmin',
                body: {
                    login: this.stepFormGroup.controls['login'].value,
                    firstname: this.stepFormGroup.controls['firstname'].value,
                    lastname: this.stepFormGroup.controls['lastname'].value,
                    password: this.stepFormGroup.controls['password'].value,
                    email: this.stepFormGroup.controls['email'].value,
                },
                route: {
                    method: 'PUT',
                    url: '../rest/installer/administrator'
                },
                description: this.translate.instant('lang.stepUserAdminActionDesc'),
                installPriority: 3
            }];
    }
    launchInstall() {
        this.tiggerInstall.emit();
    }
};
UseradminComponent.ctorParameters = () => [
    { type: _ngx_translate_core__WEBPACK_IMPORTED_MODULE_6__["TranslateService"] },
    { type: _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormBuilder"] },
    { type: _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_5__["NotificationService"] },
    { type: _installer_service__WEBPACK_IMPORTED_MODULE_7__["InstallerService"] }
];
UseradminComponent.propDecorators = {
    tiggerInstall: [{ type: _angular_core__WEBPACK_IMPORTED_MODULE_3__["Output"] }]
};
UseradminComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_3__["Component"])({
        selector: 'app-useradmin',
        template: _raw_loader_useradmin_component_html__WEBPACK_IMPORTED_MODULE_1__["default"],
        styles: [_useradmin_component_scss__WEBPACK_IMPORTED_MODULE_2__["default"]]
    })
], UseradminComponent);



/***/ }),

/***/ "QCVa":
/*!****************************************************************!*\
  !*** ./src/frontend/app/installer/installer-routing.module.ts ***!
  \****************************************************************/
/*! exports provided: InstallerRoutingModule */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "InstallerRoutingModule", function() { return InstallerRoutingModule; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "fXoL");
/* harmony import */ var _angular_router__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @angular/router */ "tyNb");
/* harmony import */ var _installer_component__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./installer.component */ "M6B2");




const routes = [
    {
        path: '',
        component: _installer_component__WEBPACK_IMPORTED_MODULE_3__["InstallerComponent"]
    }
];
let InstallerRoutingModule = class InstallerRoutingModule {
};
InstallerRoutingModule = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["NgModule"])({
        imports: [_angular_router__WEBPACK_IMPORTED_MODULE_2__["RouterModule"].forChild(routes)],
        exports: [_angular_router__WEBPACK_IMPORTED_MODULE_2__["RouterModule"]],
    })
], InstallerRoutingModule);



/***/ }),

/***/ "S2qH":
/*!*********************************************************!*\
  !*** ./src/frontend/app/installer/installer.service.ts ***!
  \*********************************************************/
/*! exports provided: InstallerService */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "InstallerService", function() { return InstallerService; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @angular/core */ "fXoL");


let InstallerService = class InstallerService {
    constructor() {
        this.steps = [];
    }
    setStep(step) {
        this.steps.push(step);
    }
    isStepAlreadyLaunched(IdsStep) {
        return this.steps.filter(step => IdsStep === step.idStep).length > 0;
    }
};
InstallerService.ctorParameters = () => [];
InstallerService = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_1__["Injectable"])()
], InstallerService);



/***/ }),

/***/ "TE+0":
/*!*************************************************************!*\
  !*** ./src/frontend/app/installer/installer.component.scss ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("::ng-deep.mat-stepper-horizontal {\n  height: 100% !important;\n}\n\n.container {\n  padding-left: 80px !important;\n  padding-right: 80px !important;\n}\n\n.previousStepButton {\n  position: fixed;\n  top: 50%;\n  left: 10px;\n  transform: translateY(-50%);\n}\n\n.previousStepButton .mat-icon {\n  font-size: 25px;\n  height: auto;\n  width: auto;\n}\n\n.nextStepButton {\n  position: fixed;\n  top: 50%;\n  right: 13px;\n  transform: translateY(-50%);\n}\n\n.nextStepButton .mat-icon {\n  font-size: 25px;\n  height: auto;\n  width: auto;\n}\n\n/*.mat-stepper-horizontal {\r\n    display: flex;\r\n    flex-direction: column;\r\n\r\n\r\n    ::ng-deep.mat-step-icon {\r\n        background-color: white;\r\n        color: #135f7f;\r\n    }\r\n\r\n    ::ng-deep.mat-step-label {\r\n        color: white;\r\n        opacity: 0.5;\r\n    }\r\n\r\n    ::ng-deep.mat-step-label-active {\r\n        opacity: 1;\r\n    }\r\n}\r\n\r\n::ng-deep.mat-step-icon {\r\n    background-color: white;\r\n    color: #135f7f;\r\n}\r\n\r\n::ng-deep.mat-step-label-active {\r\n    opacity: 1;\r\n}\r\n\r\n::ng-deep.mat-step-label {\r\n    color: white;\r\n    opacity: 0.5;\r\n}\r\n\r\n.stepIcon {\r\n    font-size: 10px !important;\r\n    height: auto !important;\r\n    width: auto !important;\r\n}\r\n\r\n::ng-deep.mat-step-label {\r\n    transition: all 0.2s;\r\n}\r\n\r\n::ng-deep.mat-step-label-selected {\r\n    font-size: 160%;\r\n    transition: all 0.2s;\r\n}\r\n\r\n::ng-deep.mat-horizontal-stepper-content {\r\n    height: 100%;\r\n}\r\n\r\n::ng-deep.mat-horizontal-content-container {\r\n    flex: 1;\r\n    padding-left: 0px !important;\r\n    padding-right: 0px !important;\r\n    padding-bottom: 0px !important;\r\n}\r\n\r\n.stepContainer{\r\n    display: flex;\r\n    flex-direction: column;\r\n    height: 100%;\r\n}\r\n\r\n.stepContent {\r\n    flex: 1;\r\n    overflow: auto;\r\n\r\n    &Title {\r\n        margin-bottom: 30px;\r\n        border-bottom: solid 1px;\r\n        padding: 0;\r\n    }\r\n}\r\n\r\n.formStep {\r\n    display: contents;\r\n}*/\n\n::ng-deep.mat-step-icon {\n  background-color: white;\n  color: #135f7f;\n}\n\n::ng-deep.mat-step-label-active {\n  opacity: 1 !important;\n}\n\n::ng-deep.mat-step-label {\n  color: white;\n  opacity: 0.5;\n}\n\n.stepIcon {\n  font-size: 10px !important;\n  height: auto !important;\n  width: auto !important;\n}\n\n::ng-deep.mat-step-label {\n  transition: all 0.2s;\n}\n\n::ng-deep.mat-step-label-selected {\n  font-size: 160%;\n  transition: all 0.2s;\n  opacity: 1;\n}\n\r\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9mcm9udGVuZC9hcHAvaW5zdGFsbGVyL2luc3RhbGxlci5jb21wb25lbnQuc2NzcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtFQUNJLHVCQUF1QjtBQUMzQjs7QUFFQTtFQUNJLDZCQUE2QjtFQUM3Qiw4QkFBOEI7QUFDbEM7O0FBRUE7RUFDSSxlQUFlO0VBQ2YsUUFBUTtFQUNSLFVBQVU7RUFDViwyQkFBMkI7QUFDL0I7O0FBTEE7RUFPUSxlQUFlO0VBQ2YsWUFBVztFQUNYLFdBQVc7QUFFbkI7O0FBRUE7RUFDSSxlQUFlO0VBQ2YsUUFBUTtFQUNSLFdBQVc7RUFDWCwyQkFBMkI7QUFDL0I7O0FBTEE7RUFPUSxlQUFlO0VBQ2YsWUFBVztFQUNYLFdBQVc7QUFFbkI7O0FBQ0E7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7RUFnRkU7O0FBQ0Y7RUFDSSx1QkFBdUI7RUFDdkIsY0FBYztBQUNsQjs7QUFFQTtFQUNJLHFCQUFxQjtBQUN6Qjs7QUFFQTtFQUNJLFlBQVk7RUFDWixZQUFZO0FBQ2hCOztBQUVBO0VBQ0ksMEJBQTBCO0VBQzFCLHVCQUF1QjtFQUN2QixzQkFBc0I7QUFDMUI7O0FBRUE7RUFDSSxvQkFBb0I7QUFDeEI7O0FBRUE7RUFDSSxlQUFlO0VBQ2Ysb0JBQW9CO0VBQ3BCLFVBQVU7QUFDZCIsImZpbGUiOiJzcmMvZnJvbnRlbmQvYXBwL2luc3RhbGxlci9pbnN0YWxsZXIuY29tcG9uZW50LnNjc3MiLCJzb3VyY2VzQ29udGVudCI6WyI6Om5nLWRlZXAubWF0LXN0ZXBwZXItaG9yaXpvbnRhbCB7XHJcbiAgICBoZWlnaHQ6IDEwMCUgIWltcG9ydGFudDtcclxufVxyXG5cclxuLmNvbnRhaW5lciB7XHJcbiAgICBwYWRkaW5nLWxlZnQ6IDgwcHggIWltcG9ydGFudDtcclxuICAgIHBhZGRpbmctcmlnaHQ6IDgwcHggIWltcG9ydGFudDtcclxufVxyXG5cclxuLnByZXZpb3VzU3RlcEJ1dHRvbiB7XHJcbiAgICBwb3NpdGlvbjogZml4ZWQ7XHJcbiAgICB0b3A6IDUwJTtcclxuICAgIGxlZnQ6IDEwcHg7XHJcbiAgICB0cmFuc2Zvcm06IHRyYW5zbGF0ZVkoLTUwJSk7XHJcblxyXG4gICAgLm1hdC1pY29uIHtcclxuICAgICAgICBmb250LXNpemU6IDI1cHg7XHJcbiAgICAgICAgaGVpZ2h0OmF1dG87XHJcbiAgICAgICAgd2lkdGg6IGF1dG87XHJcbiAgICB9XHJcbn1cclxuXHJcbi5uZXh0U3RlcEJ1dHRvbiB7XHJcbiAgICBwb3NpdGlvbjogZml4ZWQ7XHJcbiAgICB0b3A6IDUwJTtcclxuICAgIHJpZ2h0OiAxM3B4O1xyXG4gICAgdHJhbnNmb3JtOiB0cmFuc2xhdGVZKC01MCUpO1xyXG5cclxuICAgIC5tYXQtaWNvbiB7XHJcbiAgICAgICAgZm9udC1zaXplOiAyNXB4O1xyXG4gICAgICAgIGhlaWdodDphdXRvO1xyXG4gICAgICAgIHdpZHRoOiBhdXRvO1xyXG4gICAgfVxyXG59XHJcbi8qLm1hdC1zdGVwcGVyLWhvcml6b250YWwge1xyXG4gICAgZGlzcGxheTogZmxleDtcclxuICAgIGZsZXgtZGlyZWN0aW9uOiBjb2x1bW47XHJcblxyXG5cclxuICAgIDo6bmctZGVlcC5tYXQtc3RlcC1pY29uIHtcclxuICAgICAgICBiYWNrZ3JvdW5kLWNvbG9yOiB3aGl0ZTtcclxuICAgICAgICBjb2xvcjogIzEzNWY3ZjtcclxuICAgIH1cclxuXHJcbiAgICA6Om5nLWRlZXAubWF0LXN0ZXAtbGFiZWwge1xyXG4gICAgICAgIGNvbG9yOiB3aGl0ZTtcclxuICAgICAgICBvcGFjaXR5OiAwLjU7XHJcbiAgICB9XHJcblxyXG4gICAgOjpuZy1kZWVwLm1hdC1zdGVwLWxhYmVsLWFjdGl2ZSB7XHJcbiAgICAgICAgb3BhY2l0eTogMTtcclxuICAgIH1cclxufVxyXG5cclxuOjpuZy1kZWVwLm1hdC1zdGVwLWljb24ge1xyXG4gICAgYmFja2dyb3VuZC1jb2xvcjogd2hpdGU7XHJcbiAgICBjb2xvcjogIzEzNWY3ZjtcclxufVxyXG5cclxuOjpuZy1kZWVwLm1hdC1zdGVwLWxhYmVsLWFjdGl2ZSB7XHJcbiAgICBvcGFjaXR5OiAxO1xyXG59XHJcblxyXG46Om5nLWRlZXAubWF0LXN0ZXAtbGFiZWwge1xyXG4gICAgY29sb3I6IHdoaXRlO1xyXG4gICAgb3BhY2l0eTogMC41O1xyXG59XHJcblxyXG4uc3RlcEljb24ge1xyXG4gICAgZm9udC1zaXplOiAxMHB4ICFpbXBvcnRhbnQ7XHJcbiAgICBoZWlnaHQ6IGF1dG8gIWltcG9ydGFudDtcclxuICAgIHdpZHRoOiBhdXRvICFpbXBvcnRhbnQ7XHJcbn1cclxuXHJcbjo6bmctZGVlcC5tYXQtc3RlcC1sYWJlbCB7XHJcbiAgICB0cmFuc2l0aW9uOiBhbGwgMC4ycztcclxufVxyXG5cclxuOjpuZy1kZWVwLm1hdC1zdGVwLWxhYmVsLXNlbGVjdGVkIHtcclxuICAgIGZvbnQtc2l6ZTogMTYwJTtcclxuICAgIHRyYW5zaXRpb246IGFsbCAwLjJzO1xyXG59XHJcblxyXG46Om5nLWRlZXAubWF0LWhvcml6b250YWwtc3RlcHBlci1jb250ZW50IHtcclxuICAgIGhlaWdodDogMTAwJTtcclxufVxyXG5cclxuOjpuZy1kZWVwLm1hdC1ob3Jpem9udGFsLWNvbnRlbnQtY29udGFpbmVyIHtcclxuICAgIGZsZXg6IDE7XHJcbiAgICBwYWRkaW5nLWxlZnQ6IDBweCAhaW1wb3J0YW50O1xyXG4gICAgcGFkZGluZy1yaWdodDogMHB4ICFpbXBvcnRhbnQ7XHJcbiAgICBwYWRkaW5nLWJvdHRvbTogMHB4ICFpbXBvcnRhbnQ7XHJcbn1cclxuXHJcbi5zdGVwQ29udGFpbmVye1xyXG4gICAgZGlzcGxheTogZmxleDtcclxuICAgIGZsZXgtZGlyZWN0aW9uOiBjb2x1bW47XHJcbiAgICBoZWlnaHQ6IDEwMCU7XHJcbn1cclxuXHJcbi5zdGVwQ29udGVudCB7XHJcbiAgICBmbGV4OiAxO1xyXG4gICAgb3ZlcmZsb3c6IGF1dG87XHJcblxyXG4gICAgJlRpdGxlIHtcclxuICAgICAgICBtYXJnaW4tYm90dG9tOiAzMHB4O1xyXG4gICAgICAgIGJvcmRlci1ib3R0b206IHNvbGlkIDFweDtcclxuICAgICAgICBwYWRkaW5nOiAwO1xyXG4gICAgfVxyXG59XHJcblxyXG4uZm9ybVN0ZXAge1xyXG4gICAgZGlzcGxheTogY29udGVudHM7XHJcbn0qL1xyXG5cclxuOjpuZy1kZWVwLm1hdC1zdGVwLWljb24ge1xyXG4gICAgYmFja2dyb3VuZC1jb2xvcjogd2hpdGU7XHJcbiAgICBjb2xvcjogIzEzNWY3ZjtcclxufVxyXG5cclxuOjpuZy1kZWVwLm1hdC1zdGVwLWxhYmVsLWFjdGl2ZSB7XHJcbiAgICBvcGFjaXR5OiAxICFpbXBvcnRhbnQ7XHJcbn1cclxuXHJcbjo6bmctZGVlcC5tYXQtc3RlcC1sYWJlbCB7XHJcbiAgICBjb2xvcjogd2hpdGU7XHJcbiAgICBvcGFjaXR5OiAwLjU7XHJcbn1cclxuXHJcbi5zdGVwSWNvbiB7XHJcbiAgICBmb250LXNpemU6IDEwcHggIWltcG9ydGFudDtcclxuICAgIGhlaWdodDogYXV0byAhaW1wb3J0YW50O1xyXG4gICAgd2lkdGg6IGF1dG8gIWltcG9ydGFudDtcclxufVxyXG5cclxuOjpuZy1kZWVwLm1hdC1zdGVwLWxhYmVsIHtcclxuICAgIHRyYW5zaXRpb246IGFsbCAwLjJzO1xyXG59XHJcblxyXG46Om5nLWRlZXAubWF0LXN0ZXAtbGFiZWwtc2VsZWN0ZWQge1xyXG4gICAgZm9udC1zaXplOiAxNjAlO1xyXG4gICAgdHJhbnNpdGlvbjogYWxsIDAuMnM7XHJcbiAgICBvcGFjaXR5OiAxO1xyXG59Il19 */");

/***/ }),

/***/ "WGJY":
/*!*************************************************************************!*\
  !*** ./src/frontend/app/installer/docservers/docservers.component.scss ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (".stepContent {\n  margin: auto;\n}\n\n.stepContent .stepContentTitle {\n  color: #135f7f;\n  margin-bottom: 30px;\n  border-bottom: solid 1px;\n  padding: 0;\n}\n\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9mcm9udGVuZC9hcHAvaW5zdGFsbGVyL2RvY3NlcnZlcnMvZG9jc2VydmVycy5jb21wb25lbnQuc2NzcyIsInNyYy9mcm9udGVuZC9jc3MvdmFycy5zY3NzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUVBO0VBRUksWUFBWTtBQUZoQjs7QUFBQTtFQUlRLGNDTFM7RURNVCxtQkFBbUI7RUFDbkIsd0JBQXdCO0VBQ3hCLFVBQVU7QUFBbEIiLCJmaWxlIjoic3JjL2Zyb250ZW5kL2FwcC9pbnN0YWxsZXIvZG9jc2VydmVycy9kb2NzZXJ2ZXJzLmNvbXBvbmVudC5zY3NzIiwic291cmNlc0NvbnRlbnQiOlsiQGltcG9ydCAnLi4vLi4vLi4vY3NzL3ZhcnMuc2Nzcyc7XHJcblxyXG4uc3RlcENvbnRlbnQge1xyXG4gICAgLy8gbWF4LXdpZHRoOiA4NTBweDtcclxuICAgIG1hcmdpbjogYXV0bztcclxuICAgIC5zdGVwQ29udGVudFRpdGxlIHtcclxuICAgICAgICBjb2xvcjogJHByaW1hcnk7XHJcbiAgICAgICAgbWFyZ2luLWJvdHRvbTogMzBweDtcclxuICAgICAgICBib3JkZXItYm90dG9tOiBzb2xpZCAxcHg7XHJcbiAgICAgICAgcGFkZGluZzogMDtcclxuICAgIH1cclxufVxyXG5cclxuIiwiJGNvbG9yLW1haW46ICM0RjRGNEY7IC8vIGRlZmF1bHQgY29sb3IgaW4gYXBwbGljYXRpb25cclxuJHByaW1hcnk6ICMxMzVmN2Y7IC8vIG1haW4gY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb25cclxuJHNlY29uZGFyeTogI0Y5OTgzMDsgLy8gbWFpbiBjb2xvciB0aGVtZSBvZiBhcHBsaWNhdGlvblxyXG4kYWNjZW50OiAjMDA2ODQxOyAvLyBhY2NlbnQgY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb24gKGxpa2Ugc3VjY2VzcyBidXR0b25zKVxyXG4kd2FybjogIzhlM2U1MjsgLy8gd2FybmluZyBjb2xvciB0aGVtZSBvZiBhcHBsaWNhdGlvblxyXG5cclxuLy8gV0FSTklORyAhIFlPVSBNVVNUIFJFQ09NUElMQVRFIG1hYXJjaC1tYXRlcmlhbC5zY3NzIElGIFZBTFVFUyBDSEFOR0VTIl19 */");

/***/ }),

/***/ "YKdi":
/*!***************************************************************************!*\
  !*** ./src/frontend/app/installer/prerequisite/prerequisite.component.ts ***!
  \***************************************************************************/
/*! exports provided: PrerequisiteComponent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "PrerequisiteComponent", function() { return PrerequisiteComponent; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "mrSG");
/* harmony import */ var _raw_loader_prerequisite_component_html__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! raw-loader!./prerequisite.component.html */ "k6h7");
/* harmony import */ var _prerequisite_component_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./prerequisite.component.scss */ "fIQu");
/* harmony import */ var _angular_core__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @angular/core */ "fXoL");
/* harmony import */ var _angular_forms__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @angular/forms */ "3Pt+");
/* harmony import */ var _angular_common_http__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @angular/common/http */ "tk/3");
/* harmony import */ var _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @service/notification/notification.service */ "AXEc");
/* harmony import */ var rxjs__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! rxjs */ "qCKp");
/* harmony import */ var _ngx_translate_core__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @ngx-translate/core */ "sYmb");
/* harmony import */ var _environments_environment__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../../../environments/environment */ "MJ5r");
/* harmony import */ var rxjs_operators__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! rxjs/operators */ "kU1M");











let PrerequisiteComponent = class PrerequisiteComponent {
    constructor(translate, http, notify, _formBuilder) {
        this.translate = translate;
        this.http = http;
        this.notify = notify;
        this._formBuilder = _formBuilder;
        this.prerequisites = {};
        this.packagesList = {
            general: [
                {
                    label: 'phpVersionValid',
                    required: true
                },
                {
                    label: 'writable',
                    required: true
                },
            ],
            tools: [
                {
                    label: 'unoconv',
                    required: true
                },
                {
                    label: 'netcatOrNmap',
                    required: true
                },
                {
                    label: 'pgsql',
                    required: true
                },
                {
                    label: 'curl',
                    required: true
                },
                {
                    label: 'zip',
                    required: true
                },
                {
                    label: 'wkhtmlToPdf',
                    required: true
                },
                {
                    label: 'imagick',
                    required: true
                },
            ],
            phpExtensions: [
                {
                    label: 'fileinfo',
                    required: true
                }, {
                    label: 'pdoPgsql',
                    required: true
                },
                {
                    label: 'gd',
                    required: true
                },
                {
                    label: 'mbstring',
                    required: true
                },
                {
                    label: 'json',
                    required: true
                },
                {
                    label: 'gettext',
                    required: true
                },
                {
                    label: 'xml',
                    required: true
                },
            ],
            phpConfiguration: [
                {
                    label: 'errorReporting',
                    required: true
                },
                {
                    label: 'displayErrors',
                    required: true
                }
            ],
        };
        this.docMaarchUrl = `https://docs.maarch.org/gitbook/html/MaarchCourrier/${_environments_environment__WEBPACK_IMPORTED_MODULE_9__["environment"].VERSION.split('.')[0] + '.' + _environments_environment__WEBPACK_IMPORTED_MODULE_9__["environment"].VERSION.split('.')[1]}/guat/guat_prerequisites/home.html`;
    }
    ngOnInit() {
        this.stepFormGroup = this._formBuilder.group({
            firstCtrl: ['', _angular_forms__WEBPACK_IMPORTED_MODULE_4__["Validators"].required]
        });
        this.getStepData();
    }
    getStepData() {
        this.http.get(`../rest/installer/prerequisites`).pipe(Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["tap"])((data) => {
            this.prerequisites = data.prerequisites;
            Object.keys(this.packagesList).forEach(group => {
                this.packagesList[group].forEach((item, key) => {
                    this.packagesList[group][key].state = this.prerequisites[this.packagesList[group][key].label] ? 'ok' : 'ko';
                    if (this.packagesList[group][key].label === 'phpVersionValid') {
                        this.translate.setTranslation(this.translate.getDefaultLang(), {
                            lang: {
                                install_phpVersionValid_desc: this.translate.instant('lang.currentVersion') + ' : ' + this.prerequisites['phpVersion']
                            }
                        }, true);
                    }
                });
            });
            this.stepFormGroup.controls['firstCtrl'].setValue(this.checkStep());
            this.stepFormGroup.controls['firstCtrl'].markAsUntouched();
        }), Object(rxjs_operators__WEBPACK_IMPORTED_MODULE_10__["catchError"])((err) => {
            this.notify.handleSoftErrors(err);
            return Object(rxjs__WEBPACK_IMPORTED_MODULE_7__["of"])(false);
        })).subscribe();
    }
    initStep() {
        let i = 0;
        Object.keys(this.packagesList).forEach(group => {
            this.packagesList[group].forEach((item, key) => {
                if (this.packagesList[group][key].state === 'ko') {
                    this.packageItem.toArray().filter((itemKo) => itemKo._elementRef.nativeElement.id === this.packagesList[group][key].label)[0].toggle();
                }
                i++;
            });
        });
    }
    checkStep() {
        let state = 'success';
        Object.keys(this.packagesList).forEach((group) => {
            this.packagesList[group].forEach((item) => {
                if (item.state === 'ko') {
                    state = '';
                }
            });
        });
        return state;
    }
    isValidStep() {
        return this.stepFormGroup === undefined ? false : this.stepFormGroup.controls['firstCtrl'].value === 'success';
    }
    getFormGroup() {
        return this.stepFormGroup;
    }
    getInfoToInstall() {
        return [];
    }
};
PrerequisiteComponent.ctorParameters = () => [
    { type: _ngx_translate_core__WEBPACK_IMPORTED_MODULE_8__["TranslateService"] },
    { type: _angular_common_http__WEBPACK_IMPORTED_MODULE_5__["HttpClient"] },
    { type: _service_notification_notification_service__WEBPACK_IMPORTED_MODULE_6__["NotificationService"] },
    { type: _angular_forms__WEBPACK_IMPORTED_MODULE_4__["FormBuilder"] }
];
PrerequisiteComponent.propDecorators = {
    packageItem: [{ type: _angular_core__WEBPACK_IMPORTED_MODULE_3__["ViewChildren"], args: ['packageItem',] }]
};
PrerequisiteComponent = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
    Object(_angular_core__WEBPACK_IMPORTED_MODULE_3__["Component"])({
        selector: 'app-prerequisite',
        template: _raw_loader_prerequisite_component_html__WEBPACK_IMPORTED_MODULE_1__["default"],
        styles: [_prerequisite_component_scss__WEBPACK_IMPORTED_MODULE_2__["default"]]
    })
], PrerequisiteComponent);



/***/ }),

/***/ "e0dC":
/*!***********************************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/frontend/app/installer/install-action/install-action.component.html ***!
  \***********************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<div class=\"mat-dialog-content-container\">\r\n    <div mat-dialog-content [@.disabled]=\"disableAnimation\">\r\n        <mat-accordion>\r\n            <mat-expansion-panel hideToggle expanded>\r\n                <div class=\"launch-action\">\r\n                    <h2 class=\"text-center\" color=\"primary\">{{'lang.almostThere' | translate}}</h2>\r\n                    <button mat-raised-button type=\"button\" color=\"primary\"\r\n                        (click)=\"installStepAction.open();launchInstall()\" style=\"font-size: 25px;padding: 20px;\">\r\n                        <i class=\" far fa-hdd\"></i> {{'lang.launchInstall' | translate}}\r\n                    </button>\r\n                </div>\r\n            </mat-expansion-panel>\r\n            <mat-expansion-panel #installStepAction [expanded]=\"false\">\r\n                <mat-list-item *ngFor=\"let step of steps\">\r\n                    <div mat-line class=\"step\" [class.endStep]=\"step.state==='OK' || step.state==='KO'\"\r\n                        [class.currentStep]=\"step.state==='inProgress'\"><span class=\"stepLabel\">{{step.label}}</span>\r\n                        <ng-container *ngIf=\"step.state==='inProgress'\">...</ng-container>&nbsp;\r\n                        <i *ngIf=\"step.state==='OK'\" class=\"fa fa-check\" style=\"color: green\"></i>\r\n                        <i *ngIf=\"step.state==='KO'\" class=\"fa fa-times\" style=\"color: red\"></i>\r\n                        <div *ngIf=\"step.msgErr!==''\" class=\"alert-message alert-message-danger\" role=\"alert\"\r\n                            style=\"margin-top: 30px;min-width: 100%;\">\r\n                            {{step.msgErr}}\r\n                        </div>\r\n                    </div>\r\n                </mat-list-item>\r\n            </mat-expansion-panel>\r\n        </mat-accordion>\r\n    </div>\r\n    <ng-container *ngIf=\"isInstallComplete() || isInstallError() || !isInstallBegin()\">\r\n        <span class=\"divider-modal\"></span>\r\n        <div mat-dialog-actions class=\"actions\">\r\n            <button *ngIf=\"!isInstallError() && isInstallComplete()\" mat-raised-button mat-button color=\"primary\"\r\n                (click)=\"goToInstance()\">{{'lang.goToNewInstance' | translate}}</button>\r\n            <button *ngIf=\"isInstallError() || !isInstallBegin()\" mat-raised-button mat-button [mat-dialog-close]=\"\">{{'lang.cancel' | translate}}</button>\r\n        </div>\r\n    </ng-container>\r\n</div>");

/***/ }),

/***/ "fIQu":
/*!*****************************************************************************!*\
  !*** ./src/frontend/app/installer/prerequisite/prerequisite.component.scss ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (".stepContent {\n  margin: auto;\n}\n\n.stepContent .stepContentTitle {\n  color: #135f7f;\n  margin-bottom: 30px;\n  border-bottom: solid 1px;\n  padding: 0;\n}\n\n.packageItem {\n  flex: 1 !important;\n}\n\n.iconCheckPackage {\n  background: white;\n  font-size: 15px !important;\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  padding: 10px;\n  border-radius: 20px;\n  height: 35px;\n  width: 35px;\n}\n\n.icon_ok {\n  color: green;\n}\n\n.icon_ok:before {\n  content: \"\\f111\";\n}\n\n.icon_warning {\n  color: orange;\n}\n\n.icon_warning:before {\n  content: \"\\f111\";\n}\n\n.icon_ko {\n  color: red;\n}\n\n.icon_ko:before {\n  content: \"\\f111\";\n}\n\n.link {\n  text-decoration: underline;\n  color: #135f7f !important;\n}\n\n.packageName {\n  font-size: 120% !important;\n  white-space: normal !important;\n}\n\n.packageName i {\n  cursor: help;\n  opacity: 0.5;\n  color: #135f7f;\n}\n\n::ng-deep.tooltip-red {\n  background: #b71c1c;\n  font-size: 14px;\n}\n\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9mcm9udGVuZC9hcHAvaW5zdGFsbGVyL3ByZXJlcXVpc2l0ZS9wcmVyZXF1aXNpdGUuY29tcG9uZW50LnNjc3MiLCJzcmMvZnJvbnRlbmQvY3NzL3ZhcnMuc2NzcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFFQTtFQUVJLFlBQVk7QUFGaEI7O0FBQUE7RUFLUSxjQ05TO0VET1QsbUJBQW1CO0VBQ25CLHdCQUF3QjtFQUN4QixVQUFVO0FBRGxCOztBQUtBO0VBQ0ksa0JBQWtCO0FBRnRCOztBQUtBO0VBQ0ksaUJBQWlCO0VBQ2pCLDBCQUEwQjtFQUMxQixhQUFhO0VBQ2IsbUJBQW1CO0VBQ25CLHVCQUF1QjtFQUN2QixhQUFhO0VBQ2IsbUJBQW1CO0VBQ25CLFlBQVk7RUFDWixXQUFXO0FBRmY7O0FBS0E7RUFDSSxZQUFZO0FBRmhCOztBQUtBO0VBQ0ksZ0JBQWdCO0FBRnBCOztBQUtBO0VBQ0ksYUFBYTtBQUZqQjs7QUFLQTtFQUNJLGdCQUFnQjtBQUZwQjs7QUFNQTtFQUNJLFVBQVU7QUFIZDs7QUFNQTtFQUNJLGdCQUFnQjtBQUhwQjs7QUFNQTtFQUNJLDBCQUEwQjtFQUMxQix5QkFBMEI7QUFIOUI7O0FBTUE7RUFDSSwwQkFBMEI7RUFDMUIsOEJBQThCO0FBSGxDOztBQUNBO0VBSVEsWUFBWTtFQUNaLFlBQVk7RUFDWixjQ2pFUztBRGdFakI7O0FBS0E7RUFDSSxtQkFBbUI7RUFDbkIsZUFBZTtBQUZuQiIsImZpbGUiOiJzcmMvZnJvbnRlbmQvYXBwL2luc3RhbGxlci9wcmVyZXF1aXNpdGUvcHJlcmVxdWlzaXRlLmNvbXBvbmVudC5zY3NzIiwic291cmNlc0NvbnRlbnQiOlsiQGltcG9ydCAnLi4vLi4vLi4vY3NzL3ZhcnMuc2Nzcyc7XHJcblxyXG4uc3RlcENvbnRlbnQge1xyXG4gICAgLy8gbWF4LXdpZHRoOiA4NTBweDtcclxuICAgIG1hcmdpbjogYXV0bztcclxuXHJcbiAgICAuc3RlcENvbnRlbnRUaXRsZSB7XHJcbiAgICAgICAgY29sb3I6ICRwcmltYXJ5O1xyXG4gICAgICAgIG1hcmdpbi1ib3R0b206IDMwcHg7XHJcbiAgICAgICAgYm9yZGVyLWJvdHRvbTogc29saWQgMXB4O1xyXG4gICAgICAgIHBhZGRpbmc6IDA7XHJcbiAgICB9XHJcbn1cclxuXHJcbi5wYWNrYWdlSXRlbSB7XHJcbiAgICBmbGV4OiAxICFpbXBvcnRhbnQ7XHJcbn1cclxuXHJcbi5pY29uQ2hlY2tQYWNrYWdlIHtcclxuICAgIGJhY2tncm91bmQ6IHdoaXRlO1xyXG4gICAgZm9udC1zaXplOiAxNXB4ICFpbXBvcnRhbnQ7XHJcbiAgICBkaXNwbGF5OiBmbGV4O1xyXG4gICAgYWxpZ24taXRlbXM6IGNlbnRlcjtcclxuICAgIGp1c3RpZnktY29udGVudDogY2VudGVyO1xyXG4gICAgcGFkZGluZzogMTBweDtcclxuICAgIGJvcmRlci1yYWRpdXM6IDIwcHg7XHJcbiAgICBoZWlnaHQ6IDM1cHg7XHJcbiAgICB3aWR0aDogMzVweDtcclxufVxyXG5cclxuLmljb25fb2sge1xyXG4gICAgY29sb3I6IGdyZWVuO1xyXG59XHJcblxyXG4uaWNvbl9vazpiZWZvcmUge1xyXG4gICAgY29udGVudDogXCJcXGYxMTFcIjtcclxufVxyXG5cclxuLmljb25fd2FybmluZyB7XHJcbiAgICBjb2xvcjogb3JhbmdlO1xyXG59XHJcblxyXG4uaWNvbl93YXJuaW5nOmJlZm9yZSB7XHJcbiAgICBjb250ZW50OiBcIlxcZjExMVwiO1xyXG59XHJcblxyXG5cclxuLmljb25fa28ge1xyXG4gICAgY29sb3I6IHJlZDtcclxufVxyXG5cclxuLmljb25fa286YmVmb3JlIHtcclxuICAgIGNvbnRlbnQ6IFwiXFxmMTExXCI7XHJcbn1cclxuXHJcbi5saW5rIHtcclxuICAgIHRleHQtZGVjb3JhdGlvbjogdW5kZXJsaW5lO1xyXG4gICAgY29sb3I6ICRwcmltYXJ5ICFpbXBvcnRhbnQ7XHJcbn1cclxuXHJcbi5wYWNrYWdlTmFtZSB7XHJcbiAgICBmb250LXNpemU6IDEyMCUgIWltcG9ydGFudDtcclxuICAgIHdoaXRlLXNwYWNlOiBub3JtYWwgIWltcG9ydGFudDtcclxuICAgIGkge1xyXG4gICAgICAgIGN1cnNvcjogaGVscDtcclxuICAgICAgICBvcGFjaXR5OiAwLjU7XHJcbiAgICAgICAgY29sb3I6ICRwcmltYXJ5O1xyXG4gICAgfVxyXG59XHJcblxyXG46Om5nLWRlZXAudG9vbHRpcC1yZWQge1xyXG4gICAgYmFja2dyb3VuZDogI2I3MWMxYztcclxuICAgIGZvbnQtc2l6ZTogMTRweDtcclxuICB9XHJcbiAgIiwiJGNvbG9yLW1haW46ICM0RjRGNEY7IC8vIGRlZmF1bHQgY29sb3IgaW4gYXBwbGljYXRpb25cclxuJHByaW1hcnk6ICMxMzVmN2Y7IC8vIG1haW4gY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb25cclxuJHNlY29uZGFyeTogI0Y5OTgzMDsgLy8gbWFpbiBjb2xvciB0aGVtZSBvZiBhcHBsaWNhdGlvblxyXG4kYWNjZW50OiAjMDA2ODQxOyAvLyBhY2NlbnQgY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb24gKGxpa2Ugc3VjY2VzcyBidXR0b25zKVxyXG4kd2FybjogIzhlM2U1MjsgLy8gd2FybmluZyBjb2xvciB0aGVtZSBvZiBhcHBsaWNhdGlvblxyXG5cclxuLy8gV0FSTklORyAhIFlPVSBNVVNUIFJFQ09NUElMQVRFIG1hYXJjaC1tYXRlcmlhbC5zY3NzIElGIFZBTFVFUyBDSEFOR0VTIl19 */");

/***/ }),

/***/ "iOzh":
/*!***********************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/frontend/app/installer/database/database.component.html ***!
  \***********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<div class=\"stepContent\">\r\n    <h2 class=\"stepContentTitle\"><i class=\"fa fa-database\"></i> {{'lang.database' | translate}}</h2>\r\n    <div class=\"alert-message alert-message-info\" role=\"alert\" style=\"margin-top: 30px;min-width: 100%;\">\r\n        {{'lang.stepDatabase_desc' | translate}}\r\n    </div>\r\n    <form [formGroup]=\"stepFormGroup\" style=\"width: 850px;margin: auto;\">\r\n        <mat-form-field appearance=\"outline\">\r\n            <mat-label>{{'lang.host' | translate}}</mat-label>\r\n            <input matInput formControlName=\"dbHostCtrl\" required>\r\n        </mat-form-field>\r\n        <mat-form-field appearance=\"outline\">\r\n            <mat-label>{{'lang.port' | translate}}</mat-label>\r\n            <input matInput formControlName=\"dbPortCtrl\"  required>\r\n        </mat-form-field>\r\n        <mat-form-field appearance=\"outline\">\r\n            <mat-label>{{'lang.user' | translate}}</mat-label>\r\n            <input matInput formControlName=\"dbLoginCtrl\" required>\r\n        </mat-form-field>\r\n        <mat-form-field appearance=\"outline\">\r\n            <mat-label>{{'lang.password' | translate}}</mat-label>\r\n            <input [type]=\"hide ? 'password' : 'text'\" matInput formControlName=\"dbPasswordCtrl\" required>\r\n            <button mat-icon-button matSuffix color=\"primary\" (click)=\"hide = !hide\">\r\n                <mat-icon class=\"fa {{hide ? 'fa-eye-slash' : 'fa-eye'}}\"></mat-icon>\r\n            </button>\r\n        </mat-form-field>\r\n        <mat-form-field appearance=\"outline\">\r\n            <mat-label>{{'lang.dbName' | translate}}</mat-label>\r\n            <input matInput formControlName=\"dbNameCtrl\" maxlength=\"50\" required>\r\n        </mat-form-field>\r\n        <div class=\"alert-message alert-message-info\" *ngIf=\"dbExist\" role=\"alert\" style=\"margin-top: 0px;min-width: 100%;\">\r\n            {{'lang.stepEmptyDb' | translate}}\r\n        </div>\r\n        <mat-form-field appearance=\"outline\" floatLabel=\"never\">\r\n            <mat-label>{{'lang.dbSample' | translate}}</mat-label>\r\n            <mat-select formControlName=\"dbSampleCtrl\">\r\n                <mat-option *ngFor=\"let sample of dataFiles\" [value]=\"sample\">\r\n                    {{sample}}\r\n                </mat-option>\r\n            </mat-select>\r\n        </mat-form-field>\r\n        <div style=\"text-align:center;\">\r\n            <button mat-raised-button type=\"button\" color=\"primary\" (click)=\"checkConnection()\" [disabled]=\"isEmptyConnInfo() || stepFormGroup.controls['dbHostCtrl'].disabled\">\r\n                {{'lang.checkInformations' | translate}}\r\n            </button>\r\n        </div>\r\n    </form>\r\n</div>");

/***/ }),

/***/ "idS4":
/*!***********************************************************************!*\
  !*** ./src/frontend/app/installer/useradmin/useradmin.component.scss ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (".stepContent {\n  margin: auto;\n}\n\n.stepContent .stepContentTitle {\n  color: #135f7f;\n  margin-bottom: 30px;\n  border-bottom: solid 1px;\n  padding: 0;\n}\n\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9mcm9udGVuZC9hcHAvaW5zdGFsbGVyL3VzZXJhZG1pbi91c2VyYWRtaW4uY29tcG9uZW50LnNjc3MiLCJzcmMvZnJvbnRlbmQvY3NzL3ZhcnMuc2NzcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFFQTtFQUVJLFlBQVk7QUFGaEI7O0FBQUE7RUFLUSxjQ05TO0VET1QsbUJBQW1CO0VBQ25CLHdCQUF3QjtFQUN4QixVQUFVO0FBRGxCIiwiZmlsZSI6InNyYy9mcm9udGVuZC9hcHAvaW5zdGFsbGVyL3VzZXJhZG1pbi91c2VyYWRtaW4uY29tcG9uZW50LnNjc3MiLCJzb3VyY2VzQ29udGVudCI6WyJAaW1wb3J0ICcuLi8uLi8uLi9jc3MvdmFycy5zY3NzJztcclxuXHJcbi5zdGVwQ29udGVudCB7XHJcbiAgICAvLyBtYXgtd2lkdGg6IDg1MHB4O1xyXG4gICAgbWFyZ2luOiBhdXRvO1xyXG5cclxuICAgIC5zdGVwQ29udGVudFRpdGxlIHtcclxuICAgICAgICBjb2xvcjogJHByaW1hcnk7XHJcbiAgICAgICAgbWFyZ2luLWJvdHRvbTogMzBweDtcclxuICAgICAgICBib3JkZXItYm90dG9tOiBzb2xpZCAxcHg7XHJcbiAgICAgICAgcGFkZGluZzogMDtcclxuICAgIH1cclxufSIsIiRjb2xvci1tYWluOiAjNEY0RjRGOyAvLyBkZWZhdWx0IGNvbG9yIGluIGFwcGxpY2F0aW9uXHJcbiRwcmltYXJ5OiAjMTM1ZjdmOyAvLyBtYWluIGNvbG9yIHRoZW1lIG9mIGFwcGxpY2F0aW9uXHJcbiRzZWNvbmRhcnk6ICNGOTk4MzA7IC8vIG1haW4gY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb25cclxuJGFjY2VudDogIzAwNjg0MTsgLy8gYWNjZW50IGNvbG9yIHRoZW1lIG9mIGFwcGxpY2F0aW9uIChsaWtlIHN1Y2Nlc3MgYnV0dG9ucylcclxuJHdhcm46ICM4ZTNlNTI7IC8vIHdhcm5pbmcgY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb25cclxuXHJcbi8vIFdBUk5JTkcgISBZT1UgTVVTVCBSRUNPTVBJTEFURSBtYWFyY2gtbWF0ZXJpYWwuc2NzcyBJRiBWQUxVRVMgQ0hBTkdFUyJdfQ== */");

/***/ }),

/***/ "k6h7":
/*!*******************************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/frontend/app/installer/prerequisite/prerequisite.component.html ***!
  \*******************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<div class=\"stepContent\">\r\n    <h2 class=\"stepContentTitle\"><i class=\"fas fa-check-square\"></i> {{'lang.prerequisite' | translate}}</h2>\r\n    <div class=\"alert-message alert-message-info\" role=\"alert\" style=\"margin-top: 30px;min-width: 100%;\">\r\n        {{'lang.stepPrerequisite_desc' | translate}} : <a href=\"{{docMaarchUrl}}\" target=\"_blank\" class=\"link\">{{docMaarchUrl}}</a>\r\n    </div>\r\n    <mat-list style=\"background: white;\" *ngFor=\"let groupPackage of packagesList | keyvalue\">\r\n        <div mat-subheader>{{'lang.' + groupPackage.key | translate}}</div>\r\n        <mat-grid-list cols=\"3\" rowHeight=\"50px\">\r\n            <mat-grid-tile *ngFor=\"let package of packagesList[groupPackage.key]\">\r\n                <mat-list-item>\r\n                    <mat-icon mat-list-icon class=\"fa iconCheckPackage icon_{{package.state}}\"></mat-icon>\r\n                    <div mat-line class=\"packageName\">\r\n                        {{'lang.install_' + package.label | translate}} <i #packageItem=\"matTooltip\" [id]=\"package.label\" class=\"fa fa-info-circle\" \r\n                        [matTooltip]=\"'lang.install_'+package.label+'_desc' | translate\"\r\n                        [matTooltipClass]=\"package.state !== 'ok' ? 'tooltip-red' : ''\" matTooltipPosition=\"right\"></i>\r\n                    </div>\r\n                </mat-list-item>  \r\n            </mat-grid-tile>\r\n          </mat-grid-list>\r\n    </mat-list>\r\n    <div style=\"text-align: center;\">\r\n        <button mat-raised-button type=\"button\" color=\"primary\" (click)=\"getStepData()\">{{'lang.updateInformations' | translate}}</button>\r\n    </div>\r\n</div>\r\n");

/***/ }),

/***/ "nNBD":
/*!*************************************************************************!*\
  !*** ./src/frontend/app/installer/mailserver/mailserver.component.scss ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (".stepContent {\n  margin: auto;\n}\n\n.stepContent .stepContentTitle {\n  color: #135f7f;\n  margin-bottom: 30px;\n  border-bottom: solid 1px;\n  padding: 0;\n}\n\n.backgroundList {\n  display: grid;\n  grid-template-columns: repeat(5, 1fr);\n  grid-gap: 10px;\n}\n\n.selected {\n  transition: all 0.3s;\n  opacity: 1 !important;\n  border: solid 10px #F99830 !important;\n}\n\n.backgroundItem {\n  border: solid 0px #F99830;\n  opacity: 0.5;\n  transition: all 0.3s;\n  cursor: pointer;\n  height: 120px;\n  background-size: cover !important;\n}\n\n.backgroundItem:hover {\n  transition: all 0.3s;\n  opacity: 1 !important;\n}\n\n.bash {\n  background: #34495e;\n  height: 310px;\n  border-radius: 5px;\n  top: 40px;\n  display: absolute;\n  color: #fff;\n  padding: 10px;\n  margin: 10px;\n}\n\nmat-drawer-container {\n  background: none !important;\n}\n\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9mcm9udGVuZC9hcHAvaW5zdGFsbGVyL21haWxzZXJ2ZXIvbWFpbHNlcnZlci5jb21wb25lbnQuc2NzcyIsInNyYy9mcm9udGVuZC9jc3MvdmFycy5zY3NzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUdBO0VBRUksWUFBWTtBQUhoQjs7QUFDQTtFQUtRLGNDUFM7RURRVCxtQkFBbUI7RUFDbkIsd0JBQXdCO0VBQ3hCLFVBQVU7QUFGbEI7O0FBTUE7RUFDSSxhQUFhO0VBQ2IscUNBQXFDO0VBQ3JDLGNBQWM7QUFIbEI7O0FBTUE7RUFDSSxvQkFBb0I7RUFDcEIscUJBQXFCO0VBQ3JCLHFDQUF3QztBQUg1Qzs7QUFNQTtFQUNJLHlCQzFCZTtFRDJCZixZQUFZO0VBQ1osb0JBQW9CO0VBQ3BCLGVBQWU7RUFDZixhQUFhO0VBQ2IsaUNBQWlDO0FBSHJDOztBQU1BO0VBQ0ksb0JBQW9CO0VBQ3BCLHFCQUFxQjtBQUh6Qjs7QUFNQTtFQUNJLG1CQUFtQjtFQUNuQixhQUFhO0VBQ2Isa0JBQWtCO0VBQ2xCLFNBQVM7RUFDVCxpQkFBaUI7RUFDakIsV0FBVztFQUNYLGFBQWE7RUFDYixZQUFZO0FBSGhCOztBQU1BO0VBQ0ksMkJBQTJCO0FBSC9CIiwiZmlsZSI6InNyYy9mcm9udGVuZC9hcHAvaW5zdGFsbGVyL21haWxzZXJ2ZXIvbWFpbHNlcnZlci5jb21wb25lbnQuc2NzcyIsInNvdXJjZXNDb250ZW50IjpbIkBpbXBvcnQgJy4uLy4uLy4uL2Nzcy92YXJzLnNjc3MnO1xyXG5cclxuXHJcbi5zdGVwQ29udGVudCB7XHJcbiAgICAvLyBtYXgtd2lkdGg6IDg1MHB4O1xyXG4gICAgbWFyZ2luOiBhdXRvO1xyXG5cclxuICAgIC5zdGVwQ29udGVudFRpdGxlIHtcclxuICAgICAgICBjb2xvcjogJHByaW1hcnk7XHJcbiAgICAgICAgbWFyZ2luLWJvdHRvbTogMzBweDtcclxuICAgICAgICBib3JkZXItYm90dG9tOiBzb2xpZCAxcHg7XHJcbiAgICAgICAgcGFkZGluZzogMDtcclxuICAgIH1cclxufVxyXG5cclxuLmJhY2tncm91bmRMaXN0IHtcclxuICAgIGRpc3BsYXk6IGdyaWQ7XHJcbiAgICBncmlkLXRlbXBsYXRlLWNvbHVtbnM6IHJlcGVhdCg1LCAxZnIpO1xyXG4gICAgZ3JpZC1nYXA6IDEwcHg7XHJcbn1cclxuXHJcbi5zZWxlY3RlZCB7XHJcbiAgICB0cmFuc2l0aW9uOiBhbGwgMC4zcztcclxuICAgIG9wYWNpdHk6IDEgIWltcG9ydGFudDtcclxuICAgIGJvcmRlcjogc29saWQgMTBweCAkc2Vjb25kYXJ5ICFpbXBvcnRhbnQ7XHJcbn1cclxuXHJcbi5iYWNrZ3JvdW5kSXRlbSB7XHJcbiAgICBib3JkZXI6IHNvbGlkIDBweCAkc2Vjb25kYXJ5O1xyXG4gICAgb3BhY2l0eTogMC41O1xyXG4gICAgdHJhbnNpdGlvbjogYWxsIDAuM3M7XHJcbiAgICBjdXJzb3I6IHBvaW50ZXI7XHJcbiAgICBoZWlnaHQ6IDEyMHB4O1xyXG4gICAgYmFja2dyb3VuZC1zaXplOiBjb3ZlciAhaW1wb3J0YW50O1xyXG59XHJcblxyXG4uYmFja2dyb3VuZEl0ZW06aG92ZXIge1xyXG4gICAgdHJhbnNpdGlvbjogYWxsIDAuM3M7XHJcbiAgICBvcGFjaXR5OiAxICFpbXBvcnRhbnQ7XHJcbn1cclxuXHJcbi5iYXNoIHtcclxuICAgIGJhY2tncm91bmQ6ICMzNDQ5NWU7XHJcbiAgICBoZWlnaHQ6IDMxMHB4O1xyXG4gICAgYm9yZGVyLXJhZGl1czogNXB4O1xyXG4gICAgdG9wOiA0MHB4O1xyXG4gICAgZGlzcGxheTogYWJzb2x1dGU7XHJcbiAgICBjb2xvcjogI2ZmZjtcclxuICAgIHBhZGRpbmc6IDEwcHg7XHJcbiAgICBtYXJnaW46IDEwcHg7XHJcbn1cclxuXHJcbm1hdC1kcmF3ZXItY29udGFpbmVyIHtcclxuICAgIGJhY2tncm91bmQ6IG5vbmUgIWltcG9ydGFudDtcclxufSIsIiRjb2xvci1tYWluOiAjNEY0RjRGOyAvLyBkZWZhdWx0IGNvbG9yIGluIGFwcGxpY2F0aW9uXHJcbiRwcmltYXJ5OiAjMTM1ZjdmOyAvLyBtYWluIGNvbG9yIHRoZW1lIG9mIGFwcGxpY2F0aW9uXHJcbiRzZWNvbmRhcnk6ICNGOTk4MzA7IC8vIG1haW4gY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb25cclxuJGFjY2VudDogIzAwNjg0MTsgLy8gYWNjZW50IGNvbG9yIHRoZW1lIG9mIGFwcGxpY2F0aW9uIChsaWtlIHN1Y2Nlc3MgYnV0dG9ucylcclxuJHdhcm46ICM4ZTNlNTI7IC8vIHdhcm5pbmcgY29sb3IgdGhlbWUgb2YgYXBwbGljYXRpb25cclxuXHJcbi8vIFdBUk5JTkcgISBZT1UgTVVTVCBSRUNPTVBJTEFURSBtYWFyY2gtbWF0ZXJpYWwuc2NzcyBJRiBWQUxVRVMgQ0hBTkdFUyJdfQ== */");

/***/ }),

/***/ "ozdG":
/*!***************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/frontend/app/installer/installer.component.html ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<mat-sidenav-container autosize class=\"maarch-container\">\r\n    <mat-sidenav-content>\r\n        <div class=\"bg-head\">\r\n            <div class=\"bg-head-content\" [class.fullContainer]=\"appService.getViewMode()\">\r\n            </div>\r\n        </div>\r\n        <div class=\"container\" [class.fullContainer]=\"appService.getViewMode()\">\r\n            <div class=\"container-content\" style=\"overflow: hidden;\">\r\n                <mat-horizontal-stepper [@.disabled]=\"true\" *ngIf=\"!loading\" linear #stepper style=\"height: 100vh;overflow: auto;\" (selectionChange)=\"initStep($event)\">\r\n                    <mat-step label=\"install\">\r\n                        <ng-template matStepLabel>Installation</ng-template>\r\n                        <div class=\"stepContainer\">\r\n                            <div class=\"stepContent\">\r\n                                <app-welcome #stepContent #appWelcome></app-welcome>\r\n                            </div>\r\n                            <button *ngIf=\"!authService.noInstall\" mat-fab [title]=\"'lang.home' | translate\" class=\"previousStepButton\" color=\"primary\" (click)=\"gotToLogin()\">\r\n                                <mat-icon class=\"fas fa-home\"></mat-icon>\r\n                            </button>\r\n                            <button mat-fab matStepperNext [title]=\"'lang.next' | translate\" class=\"nextStepButton\" color=\"primary\">\r\n                                <mat-icon class=\"fa fa-arrow-right\"></mat-icon>\r\n                            </button>\r\n                        </div>\r\n                    </mat-step>\r\n                    <mat-step [stepControl]=\"appPrerequisite.getFormGroup()\">\r\n                        <ng-template matStepLabel>{{'lang.prerequisite' | translate}}</ng-template>\r\n                        <div class=\"stepContainer\">\r\n                            <div class=\"stepContent\">\r\n                                <app-prerequisite #appPrerequisite #stepContent></app-prerequisite>\r\n                            </div>\r\n                            <button mat-fab matStepperPrevious [title]=\"'lang.previous' | translate\" class=\"previousStepButton\" color=\"primary\">\r\n                                <mat-icon class=\"fa fa-arrow-left\"></mat-icon>\r\n                            </button>\r\n                            <button mat-fab matStepperNext [title]=\"'lang.next' | translate\" class=\"nextStepButton\" color=\"primary\" [disabled]=\"!appPrerequisite.isValidStep()\">\r\n                                <mat-icon class=\"fa fa-arrow-right\"></mat-icon>\r\n                            </button>\r\n                        </div>\r\n                    </mat-step>\r\n                    <mat-step [stepControl]=\"appDatabase.getFormGroup()\">\r\n                        <ng-template matStepLabel>{{'lang.database' | translate}}</ng-template>\r\n                        <div class=\"stepContainer\">\r\n                            <div class=\"stepContent\">\r\n                                <app-database #appDatabase #stepContent (nextStep)=\"nextStep()\"></app-database>\r\n                            </div>\r\n                            <button mat-fab matStepperPrevious [title]=\"'lang.previous' | translate\" class=\"previousStepButton\" color=\"primary\">\r\n                                <mat-icon class=\"fa fa-arrow-left\"></mat-icon>\r\n                            </button>\r\n                            <button mat-fab matStepperNext [title]=\"'lang.next' | translate\" class=\"nextStepButton\" color=\"primary\" [disabled]=\"!appDatabase.isValidStep()\">\r\n                                <mat-icon class=\"fa fa-arrow-right\"></mat-icon>\r\n                            </button>\r\n                        </div>\r\n                    </mat-step>\r\n                    <mat-step [stepControl]=\"appDocservers.getFormGroup()\">\r\n                        <ng-template matStepLabel>{{'lang.docserver' | translate}}</ng-template>\r\n                        <div class=\"stepContainer\">\r\n                            <div class=\"stepContent\">\r\n                                <app-docservers #appDocservers #stepContent (nextStep)=\"nextStep()\"></app-docservers>\r\n                            </div>\r\n                            <button mat-fab matStepperPrevious [title]=\"'lang.previous' | translate\" class=\"previousStepButton\" color=\"primary\">\r\n                                <mat-icon class=\"fa fa-arrow-left\"></mat-icon>\r\n                            </button>\r\n                            <button mat-fab matStepperNext [title]=\"'lang.next' | translate\" class=\"nextStepButton\" color=\"primary\" [disabled]=\"!appDocservers.isValidStep()\">\r\n                                <mat-icon class=\"fa fa-arrow-right\"></mat-icon>\r\n                            </button>\r\n                        </div>\r\n                    </mat-step>\r\n                    <mat-step [stepControl]=\"appCustomization.getFormGroup()\">\r\n                        <ng-template matStepLabel>{{'lang.customization' | translate}}</ng-template>\r\n                        <div class=\"stepContainer\">\r\n                            <div class=\"stepContent\">\r\n                                <app-customization #appCustomization #stepContent [appDatabase]=\"appDatabase\" [appWelcome]=\"appWelcome\"></app-customization>\r\n                            </div>\r\n                            <button mat-fab matStepperPrevious [title]=\"'lang.previous' | translate\" class=\"previousStepButton\" color=\"primary\">\r\n                                <mat-icon class=\"fa fa-arrow-left\"></mat-icon>\r\n                            </button>\r\n                            <button mat-fab matStepperNext [title]=\"'lang.next' | translate\" class=\"nextStepButton\" color=\"primary\" [disabled]=\"!appCustomization.isValidStep()\">\r\n                                <mat-icon class=\"fa fa-arrow-right\"></mat-icon>\r\n                            </button>\r\n                        </div>\r\n                    </mat-step>\r\n                    <mat-step [stepControl]=\"appUseradmin.getFormGroup()\">\r\n                        <ng-template matStepLabel>{{'lang.userAdmin' | translate}}</ng-template>\r\n                        <div class=\"stepContainer\">\r\n                            <div class=\"stepContent\">\r\n                                <app-useradmin #appUseradmin #stepContent (tiggerInstall)=\"endInstall()\"></app-useradmin>\r\n                            </div>\r\n                            <button mat-fab matStepperPrevious [title]=\"'lang.previous' | translate\" class=\"previousStepButton\" color=\"primary\">\r\n                                <mat-icon class=\"fa fa-arrow-left\"></mat-icon>\r\n                            </button>\r\n                            <button mat-fab [title]=\"'lang.beginInstall' | translate\" class=\"nextStepButton\" color=\"accent\" [disabled]=\"!appUseradmin.isValidStep()\" (click)=\"endInstall()\">\r\n                                <mat-icon class=\"fas fa-check-double\"></mat-icon>\r\n                            </button>\r\n                        </div>\r\n                    </mat-step>\r\n                    <ng-template matStepperIcon=\"edit\">\r\n                        <mat-icon class=\"fa fa-check stepIcon\"></mat-icon>\r\n                    </ng-template>\r\n                \r\n                    <ng-template matStepperIcon=\"done\">\r\n                        <mat-icon class=\"fa fa-check stepIcon\"></mat-icon>\r\n                    </ng-template>\r\n                \r\n                    <ng-template matStepperIcon=\"error\">\r\n                        <mat-icon class=\"fa fa-times stepIcon\" style=\"color: red;font-size: 15px !important;\"></mat-icon>\r\n                    </ng-template>\r\n                </mat-horizontal-stepper>\r\n            </div>\r\n        </div>\r\n    </mat-sidenav-content>\r\n</mat-sidenav-container>");

/***/ }),

/***/ "sqCo":
/*!***************************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/frontend/app/installer/docservers/docservers.component.html ***!
  \***************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<div class=\"stepContent\">\r\n    <h2 class=\"stepContentTitle\"><i class=\"fa fa-hdd\"></i> {{'lang.docserver' | translate}}</h2>\r\n    <div class=\"alert-message alert-message-info\" role=\"alert\" style=\"margin-top: 30px;min-width: 100%;\">\r\n        {{'lang.stepDocserver_desc' | translate}}\r\n    </div>\r\n    <form [formGroup]=\"stepFormGroup\" style=\"width: 850px;margin: auto;\">\r\n        <mat-form-field appearance=\"outline\" style=\"color: initial;\">\r\n            <mat-label>{{'lang.docserverPath' | translate}}</mat-label>\r\n            <input matInput formControlName=\"docserversPath\">\r\n            <span matSuffix>/__CUSTOM_iD__/</span>\r\n        </mat-form-field>\r\n        <div style=\"text-align: center;\">\r\n            <button mat-raised-button type=\"button\" color=\"primary\" (click)=\"checkAvailability()\" [disabled]=\"!this.stepFormGroup.controls['docserversPath'].valid\">{{'lang.checkInformations' | translate}}</button>\r\n        </div>\r\n    </form>\r\n</div>");

/***/ }),

/***/ "v/rb":
/*!*********************************************************************************!*\
  !*** ./src/frontend/app/installer/install-action/install-action.component.scss ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (".step {\n  opacity: 0.5;\n  transition: all 0.2s;\n}\n\n.step .stepLabel {\n  transition: all 0.2s;\n}\n\n.currentStep {\n  opacity: 1;\n  transition: all 0.2s;\n}\n\n.currentStep .stepLabel {\n  font-size: 150%;\n  transition: all 0.2s;\n}\n\n.endStep {\n  opacity: 1;\n}\n\n.mat-expansion-panel {\n  box-shadow: none;\n}\n\n.launch-action {\n  display: flex;\n  flex-direction: column;\n}\n\n/*# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9mcm9udGVuZC9hcHAvaW5zdGFsbGVyL2luc3RhbGwtYWN0aW9uL2luc3RhbGwtYWN0aW9uLmNvbXBvbmVudC5zY3NzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0VBQ0ksWUFBWTtFQUNaLG9CQUFvQjtBQUN4Qjs7QUFIQTtFQUtRLG9CQUFvQjtBQUU1Qjs7QUFFQTtFQUNJLFVBQVU7RUFPVixvQkFBb0I7QUFMeEI7O0FBSEE7RUFJUSxlQUFlO0VBQ2Ysb0JBQW9CO0FBRzVCOztBQUdBO0VBQ0ksVUFBVTtBQUFkOztBQUdBO0VBQ0ksZ0JBQWdCO0FBQXBCOztBQUdBO0VBQ0ksYUFBYTtFQUNiLHNCQUFzQjtBQUExQiIsImZpbGUiOiJzcmMvZnJvbnRlbmQvYXBwL2luc3RhbGxlci9pbnN0YWxsLWFjdGlvbi9pbnN0YWxsLWFjdGlvbi5jb21wb25lbnQuc2NzcyIsInNvdXJjZXNDb250ZW50IjpbIi5zdGVwIHtcclxuICAgIG9wYWNpdHk6IDAuNTtcclxuICAgIHRyYW5zaXRpb246IGFsbCAwLjJzO1xyXG5cclxuICAgIC5zdGVwTGFiZWwge1xyXG4gICAgICAgIHRyYW5zaXRpb246IGFsbCAwLjJzO1xyXG4gICAgfVxyXG59XHJcblxyXG4uY3VycmVudFN0ZXAge1xyXG4gICAgb3BhY2l0eTogMTtcclxuXHJcbiAgICAuc3RlcExhYmVsIHtcclxuICAgICAgICBmb250LXNpemU6IDE1MCU7XHJcbiAgICAgICAgdHJhbnNpdGlvbjogYWxsIDAuMnM7XHJcbiAgICB9XHJcblxyXG4gICAgdHJhbnNpdGlvbjogYWxsIDAuMnM7XHJcbn1cclxuXHJcbi5lbmRTdGVwIHtcclxuICAgIG9wYWNpdHk6IDE7XHJcbn1cclxuXHJcbi5tYXQtZXhwYW5zaW9uLXBhbmVsIHtcclxuICAgIGJveC1zaGFkb3c6IG5vbmU7XHJcbn1cclxuXHJcbi5sYXVuY2gtYWN0aW9uIHtcclxuICAgIGRpc3BsYXk6IGZsZXg7XHJcbiAgICBmbGV4LWRpcmVjdGlvbjogY29sdW1uO1xyXG59Il19 */");

/***/ }),

/***/ "xFLC":
/*!*********************************************************************************************************!*\
  !*** ./node_modules/raw-loader/dist/cjs.js!./src/frontend/app/installer/welcome/welcome.component.html ***!
  \*********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("<div class=\"stepContent\">\r\n    <h2 class=\"stepContentTitle\">{{'lang.welcomeApp' | translate:{value1: appVersion} }} !</h2>\r\n    <div style=\"text-align: center;\">\r\n        <mat-icon class=\"maarchLogoFull\" svgIcon=\"maarchLogoFull\"></mat-icon>\r\n    </div>\r\n    <form [formGroup]=\"stepFormGroup\" style=\"width: 850px;margin: auto;\">\r\n        <mat-form-field appearance=\"outline\" floatLabel=\"never\">\r\n            <mat-label>{{'lang.chooseAppLanguage' | translate}} : </mat-label>\r\n            <mat-select formControlName=\"lang\"  (selectionChange)=\"changeLang($event.value)\" required>\r\n                <mat-option *ngFor=\"let language of langs\" [value]=\"language\">\r\n                    {{'lang.' + language + 'Full' | translate}}\r\n                </mat-option>\r\n            </mat-select>\r\n        </mat-form-field>\r\n    </form>\r\n    <mat-divider></mat-divider>\r\n    <ng-container *ngIf=\"customs.length > 0\">\r\n        <mat-list>\r\n            <div mat-subheader>{{'lang.instancesList' | translate}} :\r\n            </div>\r\n            <mat-list-item *ngFor=\"let custom of customs\">\r\n                <mat-icon mat-list-icon color=\"primary\" class=\"fas fa-box-open\"></mat-icon>\r\n                <div mat-line>{{custom.label}} <small style=\"color: #666\">{{custom.id}}</small></div>\r\n            </mat-list-item>\r\n        </mat-list>\r\n        <mat-divider></mat-divider>\r\n    </ng-container>\r\n    <mat-list>\r\n        <div mat-subheader>{{'lang.installDescription' | translate}} :\r\n        </div>\r\n        <mat-list-item *ngFor=\"let step of steps\">\r\n            <mat-icon mat-list-icon color=\"primary\" [class]=\"step.icon\"></mat-icon>\r\n            <div mat-line>{{step.desc | translate}}</div>\r\n        </mat-list-item>\r\n    </mat-list>\r\n    <mat-divider></mat-divider>\r\n    <p>\r\n        {{'lang.externalInfoSite' | translate}} :\r\n    </p>\r\n    <a mat-raised-button color=\"primary\" href=\"https://community.maarch.org/\" target=\"_blank\">\r\n        community.maarch.org\r\n    </a>\r\n    {{'lang.or' | translate}}\r\n    <a mat-raised-button color=\"primary\" href=\"http://www.maarch.com\" target=\"_blank\">\r\n        www.maarch.com\r\n    </a>\r\n    <p style=\"font-style: italic;padding-top: 30px;text-align: right;\" [innerHTML]=\"'lang.maarchLicenceInstall' | translate\"></p>\r\n</div>");

/***/ })

}]);
//# sourceMappingURL=installer-installer-module-es2015.js.map