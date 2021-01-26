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
    templateUrl: 'shippings-administration.component.html'
})
export class ShippingsAdministrationComponent implements OnInit {

    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;

    

    shippings: any[] = [];

    loading: boolean = false;

    displayedColumns = ['label', 'description', 'accountid', 'actions'];
    filterColumns = ['label', 'description', 'accountid'];

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
        this.headerService.setHeader(this.translate.instant('lang.administration') + ' ' + this.translate.instant('lang.shippings'));

        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');

        this.loading = true;

        this.http.get('../rest/administration/shippings')
            .subscribe((data: any) => {
                this.shippings = data.shippings;

                setTimeout(() => {
                    this.adminService.setDataSource('admin_shippings', this.shippings, this.sort, this.paginator, this.filterColumns);
                }, 0);

                this.loading = false;
            });
    }

    deleteShipping(id: number) {
        const r = confirm(this.translate.instant('lang.deleteMsg'));

        if (r) {
            this.http.delete('../rest/administration/shippings/' + id)
                .subscribe((data: any) => {
                    this.shippings = data.shippings;
                    this.adminService.setDataSource('admin_shippings', this.shippings, this.sort, this.paginator, this.filterColumns);
                    this.notify.success(this.translate.instant('lang.shippingDeleted'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }
}
