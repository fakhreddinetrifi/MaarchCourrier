import { Component, ViewChild, OnInit, TemplateRef, ViewContainerRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { HeaderService } from '@service/header.service';
import { AppService } from '@service/app.service';
import { FunctionsService } from '@service/functions.service';
import { AdministrationService } from '../administration.service';

@Component({
    templateUrl: 'templates-administration.component.html'
})

export class TemplatesAdministrationComponent implements OnInit {

    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;

    
    search: string = null;

    templates: any[] = [];
    titles: any[] = [];

    loading: boolean = false;

    displayedColumns = ['template_id', 'template_label', 'template_comment', 'template_type', 'template_target', 'actions'];
    filterColumns = ['template_label', 'template_comment', 'template_type', 'template_target'];

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
        this.headerService.setHeader(this.translate.instant('lang.administration') + ' ' + this.translate.instant('lang.templates'));

        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');

        this.loading = true;

        this.http.get('../rest/templates')
            .subscribe((data) => {
                this.templates = data['templates'];
                this.loading = false;
                setTimeout(() => {
                    this.adminService.setDataSource('admin_templates', this.templates, this.sort, this.paginator, this.filterColumns);
                }, 0);
            }, (err) => {
                this.notify.handleErrors(err);
            });
    }

    deleteTemplate(template: any) {
        const r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.delete') + ' « ' + template.template_label + ' »');

        if (r) {
            this.http.delete('../rest/templates/' + template.template_id)
                .subscribe(() => {
                    for (const i in this.templates) {
                        if (this.templates[i].template_id === template.template_id) {
                            this.templates.splice(Number(i), 1);
                        }
                    }
                    this.adminService.setDataSource('admin_templates', this.templates, this.sort, this.paginator, this.filterColumns);

                    this.notify.success(this.translate.instant('lang.templateDeleted'));

                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }
}
