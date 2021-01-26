import { Component, OnInit, ViewChild, TemplateRef, ViewContainerRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router, ActivatedRoute } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { AppService } from '@service/app.service';

@Component({
    templateUrl: 'notification-administration.component.html'
})
export class NotificationAdministrationComponent implements OnInit {

    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;

    creationMode: boolean;
    notification: any = {
        diffusionType_label: null
    };
    loading: boolean = false;
    

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private route: ActivatedRoute,
        private router: Router,
        private notify: NotificationService,
        private headerService: HeaderService,
        public appService: AppService,
        private viewContainerRef: ViewContainerRef
    ) { }

    ngOnInit(): void {
        this.loading = true;

        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');

        this.route.params.subscribe((params: any) => {

            if (typeof params['identifier'] === 'undefined') {
                this.headerService.setHeader(this.translate.instant('lang.notificationCreation'));

                this.creationMode = true;
                this.http.get('../rest/administration/notifications/new')
                    .subscribe((data: any) => {
                        this.notification = data.notification;
                        this.notification.attachfor_properties = [];
                        this.loading = false;
                    }, (err: any) => {
                        this.notify.error(err.error.errors);
                    });
            } else {

                this.creationMode = false;
                this.http.get('../rest/notifications/' + params['identifier'])
                    .subscribe((data: any) => {
                        this.headerService.setHeader(this.translate.instant('lang.notificationModification'), data.notification.description);

                        this.notification = data.notification;
                        this.notification.attachfor_properties = [];
                        this.loading = false;
                    }, (err: any) => {
                        this.notify.error(err.error.errors);
                    });
            }
        });

    }

    createScript() {
        this.http.post('../rest/scriptNotification', this.notification)
            .subscribe((data: any) => {
                this.notification.scriptcreated = data;
                this.notify.success(this.translate.instant('lang.scriptCreated'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    onSubmit() {
        if (this.creationMode) {
            this.notification.is_enabled = 'Y';
            this.http.post('../rest/notifications', this.notification)
                .subscribe((data: any) => {
                    this.router.navigate(['/administration/notifications']);
                    this.notify.success(this.translate.instant('lang.notificationAdded'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        } else {
            this.http.put('../rest/notifications/' + this.notification.notification_sid, this.notification)
                .subscribe((data: any) => {
                    this.router.navigate(['/administration/notifications']);
                    this.notify.success(this.translate.instant('lang.notificationUpdated'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    toggleNotif() {
        if (this.notification.is_enabled === 'Y') {
            this.notification.is_enabled = 'N';
        } else {
            this.notification.is_enabled = 'Y';
        }
        this.http.put('../rest/notifications/' + this.notification.notification_sid, this.notification)
            .subscribe((data: any) => {
                this.notify.success(this.translate.instant('lang.notificationUpdated'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    isNumber(val: any) {
        return $.isNumeric(val);
    }
}
