import { Component, OnInit, ViewChild } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router, ActivatedRoute } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MatSidenav } from '@angular/material/sidenav';
import { HeaderService } from '@service/header.service';
import { AppService } from '@service/app.service';
import { tap, catchError } from 'rxjs/operators';
import { FunctionsService } from '@service/functions.service';
import { FormControl } from '@angular/forms';
import { of } from 'rxjs';


@Component({
    templateUrl: 'action-administration.component.html'
})
export class ActionAdministrationComponent implements OnInit {

    @ViewChild('snav2', { static: true }) public sidenavRight: MatSidenav;

    creationMode: boolean;
    action: any = {};
    statuses: any[] = [];
    actionPages: any[] = [];
    categoriesList: any[] = [];
    keywordsList: any[] = [];

    group: any[] = [];

    loading: boolean = false;
    availableCustomFields: Array<any> = [];
    customFieldsFormControl = new FormControl({ value: '', disabled: false });
    selectedFieldsValue: Array<any> = [];
    selectedFieldsId: Array<any> = [];
    selectedValue: any;
    arMode: any;

    selectActionPageId = new FormControl();
    selectStatusId = new FormControl();

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private route: ActivatedRoute,
        private router: Router,
        private notify: NotificationService,
        private headerService: HeaderService,
        public appService: AppService,
        public functions: FunctionsService) { }

    ngOnInit(): void {
        this.loading = true;

        this.route.params.subscribe(params => {

            if (typeof params['id'] === 'undefined') {

                this.creationMode = true;

                this.http.get('../rest/initAction')
                    .subscribe((data: any) => {
                        this.action = data.action;
                        this.selectActionPageId.setValue(this.action.actionPageId);
                        this.selectStatusId.setValue(this.action.id_status);
                        this.categoriesList = data.categoriesList;
                        this.statuses = data.statuses.map((status: any) => {
                            return {
                                id: status.id,
                                label: status.label_status
                            };
                        });

                        this.actionPages = data['actionPages'];
                        this.actionPages.map(action => action.category).filter((cat, index, self) => self.indexOf(cat) === index).forEach(element => {
                            this.group.push({
                                id : element,
                                label : this.translate.instant('lang.' + element)
                            });
                        });

                        this.keywordsList = data.keywordsList;
                        this.headerService.setHeader(this.translate.instant('lang.actionCreation'));
                        this.loading = false;
                    });
            } else {

                this.creationMode = false;

                this.http.get('../rest/actions/' + params['id'])
                    .subscribe(async (data: any) => {
                        this.action = data.action;
                        this.selectActionPageId.setValue(this.action.actionPageId);
                        this.selectStatusId.setValue(this.action.id_status);
                        this.categoriesList = data.categoriesList;
                        this.statuses = data.statuses.map((status: any) => {
                            return {
                                id: status.id,
                                label: status.label_status
                            };
                        });
                        this.actionPages = data['actionPages'];
                        this.actionPages.map(action => action.category).filter((cat, index, self) => self.indexOf(cat) === index).forEach(element => {
                            this.group.push({
                                id : element,
                                label : this.translate.instant('lang.' + element)
                            });
                        });
                        this.keywordsList = data.keywordsList;
                        this.headerService.setHeader(this.translate.instant('lang.actionCreation'), data.action.label_action);
                        await this.getCustomFields();
                        this.loading = false;
                        if (this.action.actionPageId === 'close_mail') {
                            this.customFieldsFormControl = new FormControl({ value: this.action.parameters.requiredFields, disabled: false });
                            this.selectedFieldsId = [];
                            if (this.action.parameters.requiredFields) {
                                this.selectedFieldsId = this.action.parameters.requiredFields;
                            }
                            this.selectedFieldsId.forEach((element: any) => {
                                this.availableCustomFields.forEach((availableElement: any) => {
                                    if (availableElement.id === element) {
                                        this.selectedFieldsValue.push(availableElement.label);
                                    }
                                });
                            });
                        } else if (this.action.actionPageId === 'create_acknowledgement_receipt') {
                            this.arMode = this.action.parameters.mode;
                        }
                    });
            }
        });
    }

    getCustomFields() {
        this.action.actionPageId = this.selectActionPageId.value;
        this.action.actionPageGroup = this.actionPages.filter(action => action.id === this.action.actionPageId)[0].category;

        if (this.action.actionPageGroup === 'registeredMail') {
            this.action.actionCategories = ['registeredMail'];
        }

        return new Promise((resolve, reject) => {
            if (this.action.actionPageId === 'close_mail' && this.functions.empty(this.availableCustomFields)) {
                this.http.get('../rest/customFields').pipe(
                    tap((data: any) => {
                        this.availableCustomFields = data.customFields.map((info: any) => {
                            info.id = 'indexingCustomField_' + info.id;
                            return info;
                        });
                        return resolve(true);
                    }),
                    catchError((err: any) => {
                        this.notify.handleSoftErrors(err);
                        return of(false);
                    })
                ).subscribe();
            } else {
                resolve(true);
            }
        });
    }

    getSelectedFields() {
        this.availableCustomFields.forEach((element: any) => {
            if (element.id === this.customFieldsFormControl.value) {
                this.selectedValue = element;
            }
        });
        if (this.selectedFieldsId.indexOf(this.customFieldsFormControl.value) < 0) {
            this.selectedFieldsValue.push(this.selectedValue.label);
            this.selectedFieldsId.push(this.customFieldsFormControl.value);
        }
        this.customFieldsFormControl.reset();
    }

    removeSelectedFields(index: number) {
        this.selectedFieldsValue.splice(index, 1);
        this.selectedFieldsId.splice(index, 1);
    }

    onSubmit() {
        if (this.action.actionPageId === 'close_mail') {
            this.action.parameters = { requiredFields: this.selectedFieldsId };
        } else if (this.action.actionPageId === 'create_acknowledgement_receipt') {
            this.action.parameters = { mode: this.arMode };
        }
        if (this.creationMode) {
            this.http.post('../rest/actions', this.action)
                .subscribe(() => {
                    this.router.navigate(['/administration/actions']);
                    this.notify.success(this.translate.instant('lang.actionAdded'));

                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        } else {
            this.http.put('../rest/actions/' + this.action.id, this.action)
                .subscribe(() => {
                    this.router.navigate(['/administration/actions']);
                    this.notify.success(this.translate.instant('lang.actionUpdated'));

                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }
}
