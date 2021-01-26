import { Component, OnInit, ViewChild, TemplateRef, ViewContainerRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { AppService } from '@service/app.service';
import { FunctionsService } from '@service/functions.service';
import { AdministrationService } from '../administration.service';

@Component({
    templateUrl: 'parameters-administration.component.html'
})
export class ParametersAdministrationComponent implements OnInit {

    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;

    parameters: any = {};

    loading: boolean = false;

    displayedColumns = ['id', 'description', 'value', 'actions'];
    filterColumns = ['id', 'description', 'value'];

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
        this.headerService.setHeader(this.translate.instant('lang.administration') + ' ' + this.translate.instant('lang.parameters'));

        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');

        this.loading = true;

        this.http.get('../rest/parameters')
            .subscribe((data: any) => {
                this.parameters = data.parameters.filter((item: any) => ['homepage_message', 'loginpage_message', 'traffic_record_summary_sheet', 'bindingDocumentFinalAction', 'nonBindingDocumentFinalAction'].indexOf(item.id) === -1);
                this.loading = false;
                setTimeout(() => {
                    this.adminService.setDataSource('admin_parameters', this.parameters, this.sort, this.paginator, this.filterColumns);
                }, 0);
            });
    }

    deleteParameter(paramId: string) {
        const r = confirm(this.translate.instant('lang.deleteMsg'));

        if (r) {
            this.http.delete('../rest/parameters/' + paramId)
                .subscribe((data: any) => {
                    this.parameters = data.parameters.filter((item: any) => ['homepage_message', 'loginpage_message', 'traffic_record_summary_sheet', 'bindingDocumentFinalAction', 'nonBindingDocumentFinalAction'].indexOf(item.id) === -1);
                    this.adminService.setDataSource('admin_parameters', this.parameters, this.sort, this.paginator, this.filterColumns);
                    this.notify.success(this.translate.instant('lang.parameterDeleted'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }
}
