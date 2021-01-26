import { Component, OnInit, ViewChild, TemplateRef, ViewContainerRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MatPaginator } from '@angular/material/paginator';
import { MatSidenav } from '@angular/material/sidenav';
import { MatSort } from '@angular/material/sort';
import { HeaderService } from '@service/header.service';
import { AppService } from '@service/app.service';
import { FunctionsService } from '@service/functions.service';
import { AdministrationService } from '../administration.service';

@Component({
    templateUrl: 'priorities-administration.component.html'
})
export class PrioritiesAdministrationComponent implements OnInit {

    @ViewChild('snav2', { static: true }) public sidenavRight: MatSidenav;
    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;

    
    loading: boolean = false;

    priorities: any[] = [];
    prioritiesOrder: any[] = [];
    displayedColumns = ['id', 'label', 'delays', 'actions'];
    filterColumns = ['id', 'label', 'delays'];


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
        this.headerService.setHeader(this.translate.instant('lang.administration') + ' ' + this.translate.instant('lang.priorities'));

        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');

        this.loading = true;

        this.http.get('../rest/priorities')
            .subscribe((data: any) => {
                this.priorities = data['priorities'];
                this.loading = false;
                this.http.get('../rest/sortedPriorities')
                    .subscribe((dataPriorities: any) => {
                        this.prioritiesOrder = dataPriorities['priorities'];
                    }, (err) => {
                        this.notify.handleErrors(err);
                    });
                    setTimeout(() => {
                        this.adminService.setDataSource('admin_priorities', this.priorities, this.sort, this.paginator, this.filterColumns);
                    }, 0);
            }, (err) => {
                this.notify.handleErrors(err);
            });
    }

    deletePriority(id: string) {
        const r = confirm(this.translate.instant('lang.deleteMsg'));

        if (r) {
            this.http.delete('../rest/priorities/' + id)
                .subscribe((data: any) => {
                    this.priorities = data['priorities'];
                    this.adminService.setDataSource('admin_priorities', this.priorities, this.sort, this.paginator, this.filterColumns);
                    this.notify.success(this.translate.instant('lang.priorityDeleted'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    updatePrioritiesOrder() {
        this.http.put('../rest/sortedPriorities', this.prioritiesOrder)
            .subscribe((data: any) => {
                this.prioritiesOrder = data['priorities'];
                this.notify.success(this.translate.instant('lang.modificationSaved'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }
}
