import { Component, ViewChild, OnInit, TemplateRef, ViewContainerRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { MatPaginator } from '@angular/material/paginator';
import { MatSidenav } from '@angular/material/sidenav';
import { MatSort } from '@angular/material/sort';
import { AppService } from '@service/app.service';
import { FunctionsService } from '@service/functions.service';
import { AdministrationService } from '../../administration.service';

@Component({
    templateUrl: 'contacts-groups-administration.component.html',
    styleUrls: [
        'contacts-groups-administration.component.scss'
    ]
})

export class ContactsGroupsAdministrationComponent implements OnInit {

    @ViewChild('snav2', { static: true }) public sidenavRight: MatSidenav;
    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;

    
    search: string = null;

    contactsGroups: any[] = [];
    titles: any[] = [];

    loading: boolean = false;

    subMenus: any[] = [
        {
            icon: 'fa fa-book',
            route: '/administration/contacts',
            label: this.translate.instant('lang.contactsList'),
            current: false
        },
        {
            icon: 'fa fa-code',
            route: '/administration/contacts/contactsCustomFields',
            label: this.translate.instant('lang.customFieldsAdmin'),
            current: false
        },
        {
            icon: 'fa fa-cog',
            route: '/administration/contacts/contacts-parameters',
            label: this.translate.instant('lang.contactsParameters'),
            current: false
        },
        {
            icon: 'fa fa-users',
            route: '/administration/contacts/contacts-groups',
            label: this.translate.instant('lang.contactsGroups'),
            current: true
        },
        {
            icon: 'fas fa-magic',
            route: '/administration/contacts/duplicates',
            label: this.translate.instant('lang.duplicatesContactsAdmin'),
            current: false
        },
    ];

    displayedColumns = ['label', 'description', 'nbContacts', 'public', 'owner', 'actions'];
    filterColumns = ['label', 'description'];

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
        this.headerService.setHeader(this.translate.instant('lang.administration') + ' ' + this.translate.instant('lang.contactsGroups'));
        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');

        this.loading = true;

        this.http.get('../rest/contactsGroups')
            .subscribe((data) => {
                this.contactsGroups = data['contactsGroups'];
                this.loading = false;
                setTimeout(() => {
                    this.adminService.setDataSource('admin_contacts_groups', this.contactsGroups, this.sort, this.paginator, this.filterColumns);
                }, 0);
            }, (err) => {
                this.notify.handleErrors(err);
            });
    }

    deleteContactsGroup(row: any) {
        const contactsGroup = this.contactsGroups[row];
        const r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.delete') + ' « ' + contactsGroup.label + ' »');

        if (r) {
            this.http.delete('../rest/contactsGroups/' + contactsGroup.id)
                .subscribe(() => {
                    const lastElement = this.contactsGroups.length - 1;
                    this.contactsGroups[row] = this.contactsGroups[lastElement];
                    this.contactsGroups[row].position = row;
                    this.contactsGroups.splice(lastElement, 1);
                    this.adminService.setDataSource('admin_contacts_groups', this.contactsGroups, this.sort, this.paginator, this.filterColumns);
                    this.notify.success(this.translate.instant('lang.contactsGroupDeleted'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }
}
