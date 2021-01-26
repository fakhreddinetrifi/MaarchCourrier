import { Component, OnInit, ViewChild, TemplateRef, ViewContainerRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { AppService } from '@service/app.service';
import { FunctionsService } from '@service/functions.service';
import { AdministrationService } from '../administration.service';

@Component({
    templateUrl: 'statuses-administration.component.html'
})
export class StatusesAdministrationComponent implements OnInit {

    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;

    
    loading: boolean = false;

    statuses: Status[] = [];

    displayedColumns = ['img_filename', 'id', 'label_status', 'identifier'];
    filterColumns = ['id', 'label_status'];

    @ViewChild(MatPaginator, { static: false }) paginator: MatPaginator;
    @ViewChild(MatSort, { static: false }) sort: MatSort;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        private headerService: HeaderService,
        public appService: AppService,
        public functions: FunctionsService,
        public adminService: AdministrationService,
        private viewContainerRef: ViewContainerRef
    ) { }

    ngOnInit(): void {
        this.headerService.setHeader(this.translate.instant('lang.administration') + ' ' + this.translate.instant('lang.statuses'));

        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');

        this.loading = true;

        this.http.get('../rest/statuses')
            .subscribe((data: any) => {
                this.statuses = data.statuses;
                this.loading = false;
                setTimeout(() => {
                    this.adminService.setDataSource('admin_status', this.statuses, this.sort, this.paginator, this.filterColumns);
                }, 0);

            }, (err: any) => {
                this.notify.error(err.error.errors);
            });
    }

    deleteStatus(status: any) {
        const resp = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.delete') + ' « ' + status.id + ' »');
        if (resp) {
            this.http.delete('../rest/statuses/' + status.identifier)
                .subscribe((data: any) => {
                    this.statuses = data.statuses;
                    this.adminService.setDataSource('admin_status', this.statuses, this.sort, this.paginator, this.filterColumns);
                    this.notify.success(this.translate.instant('lang.statusDeleted'));

                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

}

export interface Status {
    id: string;
    can_be_modified: string;
    can_be_searchead: string;
    identifier: number;
    img_filename: string;
    is_system: string;
    label_status: string;
    maarch_module: string;
}
