import { Component, OnInit, ViewChild, TemplateRef, ViewContainerRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { MatSidenav } from '@angular/material/sidenav';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { AppService } from '@service/app.service';
import { NgForm } from '@angular/forms';

@Component({
    templateUrl: 'sendmail-administration.component.html',
    styleUrls: ['sendmail-administration.component.scss']
})
export class SendmailAdministrationComponent implements OnInit {

    @ViewChild('snav2', { static: true }) public sidenavRight: MatSidenav;
    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;

    @ViewChild('sendmailForm', { static: false }) public sendmailFormCpt: NgForm;

    
    loading: boolean = false;

    sendmail: any = {
        'type': 'smtp',
        'host': '',
        'auth': true,
        'user': '',
        'password': '',
        'secure': 'ssl', // tls, ssl, starttls
        'port': '465',
        'charset': 'utf-8',
        'from': '',
    };

    smtpTypeList = [
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
    smtpSecList = [
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
    sendmailClone: any = {};
    hidePassword: boolean = true;
    serverConnectionLoading: boolean = false;
    emailSendLoading: boolean = false;
    emailSendResult = {
        icon: '',
        msg: '',
        debug: ''
    };
    currentUser: any = {};
    recipientTest: string = '';
    passwordLabel: string = '';



    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        private headerService: HeaderService,
        public appService: AppService,
        private viewContainerRef: ViewContainerRef
    ) { }

    ngOnInit(): void {
        this.headerService.setHeader(this.translate.instant('lang.sendmailShort'));

        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');

        this.loading = true;

        this.http.get('../rest/configurations/admin_email_server')
            .subscribe((data: any) => {
                this.sendmail = data.configuration.value;
                this.sendmailClone = JSON.parse(JSON.stringify(this.sendmail));

                this.loading = false;
            }, (err) => {
                this.notify.handleErrors(err);
            });
    }

    cancelModification() {
        this.sendmail = JSON.parse(JSON.stringify(this.sendmailClone));
    }

    onSubmit() {
        if (this.sendmailFormCpt.invalid) {
            this.notify.handleErrors({ 'error': { 'errors': this.translate.instant('lang.notSavedBecauseInvalid') } });
        } else {
            this.http.put('../rest/configurations/admin_email_server', this.sendmail)
                .subscribe((data: any) => {
                    this.sendmailClone = JSON.parse(JSON.stringify(this.sendmail));
                    this.notify.success(this.translate.instant('lang.configurationUpdated'));
                }, (err) => {
                    this.notify.handleErrors(err);
                });
        }
    }

    checkModif() {
        return (JSON.stringify(this.sendmailClone) === JSON.stringify(this.sendmail));
    }

    initEmailSend() {
        this.emailSendResult = {
            icon: '',
            msg: '',
            debug: '',
        };
        if (this.currentUser.mail === undefined) {
            this.http.get('../rest/currentUser/profile')
                .subscribe((data: any) => {
                    this.currentUser = data;
                    this.recipientTest = data.mail;
                });
        }
    }

    testEmailSend() {
        if (JSON.stringify(this.sendmailClone) !== JSON.stringify(this.sendmail)) {
            this.onSubmit();
        }
        this.emailSendResult = {
            icon: 'fa-paper-plane primary',
            msg: this.translate.instant('lang.emailSendInProgress'),
            debug: ''
        };
        const email = {
            'sender': { 'email': this.currentUser.mail },
            'recipients': [this.recipientTest],
            'object': '[' + this.translate.instant('lang.doNotReply') + '] ' + this.translate.instant('lang.emailSendTest'),
            'status': 'EXPRESS',
            'body': this.translate.instant('lang.emailSendTest'),
            'isHtml': false
        };
        this.emailSendLoading = true;

        this.http.post('../rest/emails', email)
            .subscribe((data: any) => {
                this.emailSendLoading = false;
                this.emailSendResult = {
                    icon: 'fa-check green',
                    msg: this.translate.instant('lang.emailSendSuccess'),
                    debug: ''
                };
            }, (err) => {
                this.emailSendLoading = false;
                this.emailSendResult = {
                    icon: 'fa-times red',
                    msg: this.translate.instant('lang.emailSendFailed'),
                    debug: err.error.errors
                };
            });
    }

    cleanAuthInfo(event: any) {
        this.sendmail.passwordAlreadyExists = false;

        this.sendmail.user = '';
        this.sendmail.password = '';
    }
}
