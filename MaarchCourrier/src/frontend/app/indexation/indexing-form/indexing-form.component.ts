import { Component, OnInit, Input, ViewChild, EventEmitter, Output } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { AppService } from '@service/app.service';
import { tap, catchError, exhaustMap, filter } from 'rxjs/operators';
import { of } from 'rxjs';
import { SortPipe } from '../../../plugins/sorting.pipe';
import { CdkDragDrop, moveItemInArray, transferArrayItem } from '@angular/cdk/drag-drop';
import { FormControl, Validators, FormGroup, ValidationErrors, ValidatorFn, AbstractControl } from '@angular/forms';
import { DiffusionsListComponent } from '../../diffusions/diffusions-list.component';
import { FunctionsService } from '@service/functions.service';
import { ConfirmComponent } from '../../../plugins/modal/confirm.component';
import { IssuingSiteInputComponent } from '../../administration/registered-mail/issuing-site/indexing/issuing-site-input.component';
import { RegisteredMailRecipientInputComponent } from '../../administration/registered-mail/indexing/recipient-input.component';

@Component({
    selector: 'app-indexing-form',
    templateUrl: 'indexing-form.component.html',
    styleUrls: ['indexing-form.component.scss'],
    providers: [SortPipe]
})

export class IndexingFormComponent implements OnInit {

    loading: boolean = true;

    @Input() indexingFormId: number;
    @Input() resId: number = null;
    @Input() groupId: number;
    @Input('admin') adminMode: boolean;
    @Input() canEdit: boolean = true;
    @Input() mode: string = 'indexation';

    @Input() hideDiffusionList: boolean = false;

    @Output() retrieveDocumentEvent = new EventEmitter<string>();
    @Output() loadingFormEndEvent = new EventEmitter<string>();
    @Output() afterSaveEvent = new EventEmitter<string>();

    @ViewChild('appDiffusionsList', { static: false }) appDiffusionsList: DiffusionsListComponent;
    @ViewChild('appIssuingSiteInput', { static: false }) appIssuingSiteInput: IssuingSiteInputComponent;
    @ViewChild('appRegisteredMailRecipientInput', { static: false }) appRegisteredMailRecipientInput: RegisteredMailRecipientInputComponent;

    fieldCategories: any[] = ['mail', 'contact', 'process', 'classifying'];

    indexingModelsCore: any[] = [
        {
            identifier: 'doctype',
            label: this.translate.instant('lang.doctype'),
            unit: 'mail',
            type: 'select',
            system: true,
            mandatory: true,
            enabled: true,
            default_value: '',
            values: []
        },
        {
            identifier: 'subject',
            label: this.translate.instant('lang.subject'),
            unit: 'mail',
            type: 'string',
            system: true,
            mandatory: true,
            enabled: true,
            default_value: '',
            values: []
        },
    ];

    indexingModels_mail: any[] = [];
    indexingModels_contact: any[] = [];
    indexingModels_process: any[] = [];
    indexingModels_classement: any[] = [];

    indexingModels_mailClone: any[] = [];
    indexingModels_contactClone: any[] = [];
    indexingModels_processClone: any[] = [];
    indexingModels_classementClone: any[] = [];

    indexingModelsCustomFields: any[] = [];

    availableFields: any[] = [
        {
            identifier: 'recipients',
            label: this.translate.instant('lang.getRecipients'),
            type: 'autocomplete',
            default_value: [],
            values: [],
            enabled: true,
        },
        {
            identifier: 'priority',
            label: this.translate.instant('lang.priority'),
            type: 'select',
            default_value: null,
            values: [],
            enabled: true,
        },
        {
            identifier: 'confidentiality',
            label: this.translate.instant('lang.confidential'),
            type: 'radio',
            default_value: null,
            values: [{ 'id': true, 'label': this.translate.instant('lang.yes') }, { 'id': false, 'label': this.translate.instant('lang.no') }],
            enabled: true,
        },
        {
            identifier: 'initiator',
            label: this.translate.instant('lang.initiatorEntityAlt'),
            type: 'select',
            default_value: null,
            values: [],
            enabled: true,
        },
        {
            identifier: 'departureDate',
            label: this.translate.instant('lang.departureDate'),
            type: 'date',
            default_value: null,
            values: [],
            enabled: true,
        },
        {
            identifier: 'processLimitDate',
            label: this.translate.instant('lang.processLimitDate'),
            type: 'date',
            default_value: null,
            values: [],
            enabled: true,
        },
        {
            identifier: 'tags',
            label: this.translate.instant('lang.tags'),
            type: 'autocomplete',
            default_value: [],
            values: ['/rest/autocomplete/tags', '/rest/tags'],
            enabled: true,
        },
        {
            identifier: 'senders',
            label: this.translate.instant('lang.getSenders'),
            type: 'autocomplete',
            default_value: [],
            values: ['/rest/autocomplete/correspondents'],
            enabled: true,
        },
        {
            identifier: 'destination',
            label: this.translate.instant('lang.destination'),
            type: 'select',
            default_value: null,
            values: [],
            enabled: true,
        },
        {
            identifier: 'folders',
            label: this.translate.instant('lang.folders'),
            type: 'autocomplete',
            default_value: [],
            values: ['/rest/autocomplete/folders', '/rest/folders'],
            enabled: true,
        },
        {
            identifier: 'documentDate',
            label: this.translate.instant('lang.docDate'),
            unit: 'mail',
            type: 'date',
            default_value: null,
            values: [],
            enabled: true,
        },
        {
            identifier: 'arrivalDate',
            label: this.translate.instant('lang.arrivalDate'),
            unit: 'mail',
            type: 'date',
            default_value: null,
            values: [],
            enabled: true,
        },
        {
            identifier: 'registeredMail_type',
            label: this.translate.instant('lang.registeredMailType'),
            type: 'select',
            default_value: null,
            values: [{ 'id': '2D', 'label': this.translate.instant('lang.registeredMail_2D') }, { 'id': '2C', 'label': this.translate.instant('lang.registeredMail_2C') }, { 'id': 'RW', 'label': this.translate.instant('lang.registeredMail_RW') }],
            enabled: true,
        },
        {
            identifier: 'registeredMail_issuingSite',
            label: this.translate.instant('lang.issuingSite'),
            type: 'issuingSite',
            default_value: null,
            values: [],
            enabled: true,
        },
        {
            identifier: 'registeredMail_number',
            label: this.translate.instant('lang.registeredMailNumber'),
            type: 'string',
            default_value: null,
            values: [],
            enabled: false,
        },
        {
            identifier: 'registeredMail_warranty',
            label: this.translate.instant('lang.warrantyLevel'),
            type: 'radio',
            default_value: null,
            values: [{ 'id': 'R1', 'label': 'R1' }, { 'id': 'R2', 'label': 'R2' }, { 'id': 'R3', 'label': 'R3' }],
            enabled: true,
        },
        {
            identifier: 'registeredMail_letter',
            label: this.translate.instant('lang.letter'),
            type: 'radio',
            default_value: null,
            values: [{ 'id': true, 'label': this.translate.instant('lang.yes') }, { 'id': false, 'label': this.translate.instant('lang.no') }],
            enabled: true,
        },
        {
            identifier: 'registeredMail_recipient',
            label: this.translate.instant('lang.registeredMailRecipient'),
            type: 'autocomplete',
            default_value: null,
            values: ['/rest/autocomplete/correspondents'],
            enabled: true,
        },
        {
            identifier: 'registeredMail_reference',
            label: this.translate.instant('lang.registeredMailReference'),
            type: 'string',
            default_value: null,
            values: [],
            enabled: true,
        },
    ];
    availableFieldsClone: any[] = [];

    availableCustomFields: any[] = [];
    availableCustomFieldsClone: any[] = null;

    indexingFormGroup: FormGroup;

    arrFormControl: any = {};

    currentCategory: string = '';
    currentPriorityColor: string = '';

    currentResourceValues: any = null;

    selfDest: boolean = false;
    customDiffusion: any = [];

    dialogRef: MatDialogRef<any>;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        public dialog: MatDialog,
        private headerService: HeaderService,
        public appService: AppService,
        public functions: FunctionsService
    ) {

    }

    async ngOnInit(): Promise<void> {
        this.adminMode === undefined ? this.adminMode = false : this.adminMode = true;

        this.availableFieldsClone = JSON.parse(JSON.stringify(this.availableFields));

        this.fieldCategories.forEach(category => {
            this['indexingModels_' + category] = [];
        });

        if (this.indexingFormId <= 0 || this.indexingFormId === undefined) {

            await this.initFields();
            await this.initCustomFields();

            this.initElemForm();
        } else {
            this.loadForm(this.indexingFormId);
        }
    }

    initFields() {
        return new Promise((resolve, reject) => {
            this.fieldCategories.forEach(element => {
                this['indexingModels_' + element] = this.indexingModelsCore.filter((x: any, i: any, a: any) => x.unit === element);
                this['indexingModels_' + element].forEach((field: any) => {
                    this.initValidator(field);
                });
            });
            resolve(true);
        });
    }

    initCustomFields() {
        return new Promise((resolve, reject) => {

            this.http.get('../rest/customFields').pipe(
                tap((data: any) => {
                    const withFormMode = data.customFields.filter((item: { mode: any; }) => item.mode === 'form');
                    this.availableCustomFields = withFormMode.map((info: any) => {
                        info.identifier = 'indexingCustomField_' + info.id;
                        info.system = false;
                        info.enabled = true;
                        info.SQLMode = info.SQLMode;

                        if (['integer', 'string', 'date'].indexOf(info.type) > -1 && !this.functions.empty(info.values)) {
                            info.default_value = info.values[0].key;
                        } else {
                            info.default_value = ['contact', 'banAutocomplete'].indexOf(info.type) > -1 ? [] : null;
                        }
                        info.values = info.values.length > 0 ? info.values.map((custVal: any) => {
                            return {
                                id: custVal.key,
                                label: custVal.label
                            };
                        }) : info.values;
                        return info;
                    });
                    this.availableCustomFieldsClone = JSON.parse(JSON.stringify(this.availableCustomFields));
                    resolve(true);
                }),
                catchError((err: any) => {
                    this.notify.handleErrors(err);
                    return of(false);
                })
            ).subscribe();
        });
    }

    drop(event: CdkDragDrop<string[]>) {
        event.item.data.unit = event.container.id.split('_')[1];

        if (event.previousContainer === event.container) {
            moveItemInArray(event.container.data, event.previousIndex, event.currentIndex);
        } else {
            const regex = /registeredMail_[.]*/g;

            if (event.item.data.identifier.match(regex) !== null && event.previousContainer.id === 'fieldsList') {
                this.transferRegisteredMailInput(event);
            } else {
                this.transferInput(event);
            }
            if (['destination', 'priority'].indexOf(event.item.data.identifier) > -1) {
                this.initElemForm();
            }
        }
    }

    transferInput(event: CdkDragDrop<string[]>) {
        this.initValidator(event.item.data);
        transferArrayItem(event.previousContainer.data,
            event.container.data,
            event.previousIndex,
            event.currentIndex);
    }

    onSubmit() {
        let arrIndexingModels: any[] = [];
        this.fieldCategories.forEach(category => {
            arrIndexingModels = arrIndexingModels.concat(this['indexingModels_' + category]);
        });
    }

    removeItem(arrTarget: string, item: any, index: number) {
        this.dialogRef = this.dialog.open(ConfirmComponent, { panelClass: 'maarch-modal', autoFocus: false, disableClose: true, data: { title: this.translate.instant('lang.indexingModelModification'), msg: this.translate.instant('lang.updateIndexingFieldWarning') } });

        this.dialogRef.afterClosed().pipe(
            filter((data: string) => data === 'ok'),
            tap(() => {
                item.mandatory = false;
                item.enabled = true;
                if (item.identifier.indexOf('indexingCustomField') > -1) {
                    this.availableCustomFields.push(item);
                    this[arrTarget].splice(index, 1);
                } else {
                    this.availableFields.push(item);
                    this[arrTarget].splice(index, 1);
                }
            }),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    getDatas(withDiffusionList = true) {
        let arrIndexingModels: any[] = [];
        this.fieldCategories.forEach(category => {
            arrIndexingModels = arrIndexingModels.concat(this['indexingModels_' + category]);
        });
        arrIndexingModels.forEach(element => {
            if (element.type === 'date' && !this.functions.empty(this.arrFormControl[element.identifier].value)) {
                if (element.today === true) {
                    if (this.adminMode) {
                        element.default_value = '_TODAY';
                    } else {
                        element.default_value = this.functions.formatDateObjectToDateString(this.arrFormControl[element.identifier].value, false);
                    }
                } else {
                    if (element.identifier === 'processLimitDate') {
                        element.default_value = this.functions.formatDateObjectToDateString(this.arrFormControl[element.identifier].value, true);
                    } else {
                        element.default_value = this.functions.formatDateObjectToDateString(this.arrFormControl[element.identifier].value, false);
                    }
                }
            } else {
                element.default_value = this.arrFormControl[element.identifier].value === '' ? null : this.arrFormControl[element.identifier].value;
            }

            if (element.identifier === 'destination' && !this.adminMode && withDiffusionList) {
                arrIndexingModels.push({
                    identifier: 'diffusionList',
                    default_value: this.arrFormControl['diffusionList'].value
                });
            }

        });

        if (!this.adminMode) {
            arrIndexingModels.push({
                identifier: 'modelId',
                default_value: this.indexingFormId
            });

            if (this.mode === 'indexation') {
                arrIndexingModels.push({
                    identifier: 'followed',
                    default_value: this.arrFormControl['mail­tracking'].value
                });
            }
        }

        return arrIndexingModels;
    }

    saveData() {
        return new Promise((resolve, reject) => {
            if (this.isValidForm()) {
                const formatdatas = this.formatDatas(this.getDatas());

                this.http.put(`../rest/resources/${this.resId}`, formatdatas).pipe(
                    tap(() => {
                        if (this.currentCategory === 'registeredMail') {
                            this.http.put(`../rest/registeredMails/${this.resId}`, {
                                type: formatdatas.registeredMail_type,
                                warranty: formatdatas.registeredMail_warranty,
                                issuingSiteId: formatdatas.registeredMail_issuingSite,
                                letter: formatdatas.registeredMail_letter,
                                recipient: formatdatas.registeredMail_recipient,
                                reference: formatdatas.registeredMail_reference
                            }).pipe(
                                tap(() => {
                                    this.loadForm(this.indexingFormId);
                                    this.afterSaveEvent.emit();
                                }),
                                catchError((err: any) => {
                                    this.notify.handleErrors(err);
                                    return of(false);
                                })
                            ).subscribe();
                        }
                    }),
                    tap(() => {
                        this.currentResourceValues = JSON.parse(JSON.stringify(this.getDatas(false)));
                        this.notify.success(this.translate.instant('lang.dataUpdated'));
                        resolve(true);
                    }),
                    catchError((err: any) => {
                        this.notify.handleErrors(err);
                        return of(false);
                    })
                ).subscribe();
                return true;
            } else {
                this.notify.error(this.translate.instant('lang.mustFixErrors'));
                return false;
            }
        });

    }

    formatDatas(datas: any) {
        let formatData: any = {};
        const regex = /indexingCustomField_[.]*/g;

        formatData['customFields'] = {};

        datas.forEach((element: any) => {

            if (element.identifier.match(regex) !== null) {

                formatData['customFields'][element.identifier.split('_')[1]] = element.default_value;

                // } else if (element.identifier === 'registeredMail_recipient') {
                //     formatData[element.identifier] = this.appRegisteredMailRecipientInput.getFormatedAdress();
            } else {
                formatData[element.identifier] = element.default_value;
            }
        });
        return formatData;
    }

    getCategory() {
        return this.currentCategory;
    }

    getAvailableFields() {
        return this.availableFields;
    }

    getAvailableCustomFields() {
        return this.availableCustomFields;
    }

    isModified() {
        let state = false;
        let compare: string = '';
        let compareClone: string = '';

        this.fieldCategories.forEach(category => {

            compare = JSON.stringify((this['indexingModels_' + category]));
            compareClone = JSON.stringify((this['indexingModels_' + category + 'Clone']));

            if (compare !== compareClone) {
                state = true;
            }
        });
        return state;
    }

    isResourceModified() {
        if (this.loading || JSON.stringify(this.currentResourceValues) === JSON.stringify(this.getDatas(false))) {
            return false;
        } else {
            return true;
        }
    }

    setModification() {
        this.fieldCategories.forEach(element => {
            this['indexingModels_' + element + 'Clone'] = JSON.parse(JSON.stringify(this['indexingModels_' + element]));
        });
    }

    cancelModification() {
        this.fieldCategories.forEach(element => {
            this['indexingModels_' + element] = JSON.parse(JSON.stringify(this['indexingModels_' + element + 'Clone']));
        });
    }

    setDocumentDateField(elem: any) {
        elem.startDate = '';
        elem.endDate = '_TODAY';

        this.fieldCategories.forEach(element => {
            if (this['indexingModels_' + element].filter((field: any) => field.identifier === 'arrivalDate').length > 0) {
                elem.endDate = 'arrivalDate';
            } else if (this['indexingModels_' + element].filter((field: any) => field.identifier === 'departureDate').length > 0) {
                elem.endDate = 'departureDate';
            }
        });
    }

    setDestinationField(elem: any) {
        let route = this.adminMode || this.mode !== 'indexation' ? `../rest/indexingModels/entities` : `../rest/indexing/groups/${this.groupId}/entities`;

        return new Promise((resolve, reject) => {
            this.http.get(route).pipe(
                tap((data: any) => {
                    if (this.adminMode) {
                        let title = '';
                        elem.values = [
                            {
                                id: '#myPrimaryEntity',
                                title: this.translate.instant('lang.myPrimaryEntity'),
                                label: `<i class="fa fa-hashtag"></i>&nbsp;${this.translate.instant('lang.myPrimaryEntity')}`,
                                disabled: false
                            }
                        ];
                        elem.values = elem.values.concat(data.entities.map((entity: any) => {
                            title = entity.entity_label;

                            for (let index = 0; index < entity.level; index++) {
                                entity.entity_label = '&nbsp;&nbsp;&nbsp;&nbsp;' + entity.entity_label;
                            }
                            return {
                                id: entity.id,
                                title: title,
                                label: entity.entity_label,
                                disabled: false
                            };
                        }));
                    } else {
                        let title = '';
                        if (elem.default_value === '#myPrimaryEntity') {
                            this.selfDest = this.currentCategory === 'outgoing';
                            elem.default_value = this.headerService.user.entities[0]?.id;
                            this.arrFormControl[elem.identifier].setValue(elem.default_value);
                        } else {
                            this.selfDest = false;
                            const defaultVal = data.entities.filter((entity: any) => entity.enabled === true && entity.id === elem.default_value);
                            elem.default_value = defaultVal.length > 0 ? defaultVal[0].id : null;
                            this.arrFormControl[elem.identifier].setValue(defaultVal.length > 0 ? defaultVal[0].id : '');
                        }
                        elem.values = data.entities.map((entity: any) => {
                            title = entity.entity_label;

                            for (let index = 0; index < entity.level; index++) {
                                entity.entity_label = '&nbsp;&nbsp;&nbsp;&nbsp;' + entity.entity_label;
                            }
                            return {
                                id: entity.id,
                                title: title,
                                label: entity.entity_label,
                                disabled: !entity.enabled
                            };
                        });
                        elem.event = 'loadDiffusionList';
                        elem.allowedEntities = elem.values.filter((val: any) => val.disabled === false).map((entities: any) => entities.id);
                    }
                    resolve(true);
                })
            ).subscribe();
        });

    }

    setInitiatorField(elem: any) {
        elem.values = this.headerService.user.entities.map((entity: any) => {
            return {
                id: entity.id,
                label: entity.entity_label
            };
        });
    }

    setCategoryField(elem: any) {
        return new Promise((resolve, reject) => {
            this.http.get(`../rest/categories`).pipe(
                tap((data: any) => {
                    elem.values = data.categories;
                    resolve(true);
                })
            ).subscribe();
        });
    }

    setPriorityField(elem: any) {
        return new Promise((resolve, reject) => {
            this.http.get(`../rest/priorities`).pipe(
                tap((data: any) => {
                    elem.values = data.priorities;
                    elem.event = 'calcLimitDateByPriority';
                    if (elem.default_value !== null) {
                        this.calcLimitDateByPriority(elem, elem.default_value);
                    }
                    resolve(true);
                })
            ).subscribe();
        });
    }

    setDoctypeField(elem: any) {
        return new Promise((resolve, reject) => {
            this.http.get(`../rest/doctypes`).pipe(
                tap((data: any) => {
                    let arrValues: any[] = [];
                    data.structure.forEach((doctype: any) => {
                        if (doctype['doctypes_second_level_id'] === undefined) {
                            arrValues.push({
                                id: doctype.doctypes_first_level_id,
                                label: doctype.doctypes_first_level_label,
                                title: doctype.doctypes_first_level_label,
                                disabled: true,
                                isTitle: true,
                                color: doctype.css_style
                            });
                            data.structure.filter((info: any) => info.doctypes_first_level_id === doctype.doctypes_first_level_id && info.doctypes_second_level_id !== undefined && info.description === undefined).forEach((secondDoctype: any) => {
                                arrValues.push({
                                    id: secondDoctype.doctypes_second_level_id,
                                    label: '&nbsp;&nbsp;&nbsp;&nbsp;' + secondDoctype.doctypes_second_level_label,
                                    title: secondDoctype.doctypes_second_level_label,
                                    disabled: true,
                                    isTitle: true,
                                    color: secondDoctype.css_style
                                });
                                arrValues = arrValues.concat(data.structure.filter((infoDoctype: any) => infoDoctype.doctypes_second_level_id === secondDoctype.doctypes_second_level_id && infoDoctype.description !== undefined).map((infoType: any) => {
                                    return {
                                        id: infoType.type_id,
                                        label: '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + infoType.description,
                                        title: infoType.description,
                                        disabled: false,
                                        isTitle: false,
                                    };
                                }));
                            });
                        }
                    });
                    elem.values = arrValues;
                    elem.event = 'calcLimitDate';
                    if (!this.functions.empty(elem.default_value) && !this.adminMode) {
                        this.calcLimitDate(elem, elem.default_value);
                    }
                    resolve(true);
                })
            ).subscribe();
        });
    }

    async initElemForm(saveResourceState: boolean = true) {
        this.loading = true;

        if (!this.adminMode) {
            this.arrFormControl['mail­tracking'].setValue(false);
        }
        this.currentPriorityColor = '';


        await Promise.all(this.fieldCategories.map(async (element) => {
            await Promise.all(this['indexingModels_' + element].map(async (elem: any) => {
                if (elem.identifier === 'documentDate') {
                    this.setDocumentDateField(elem);

                } else if (elem.identifier === 'destination') {
                    await this.setDestinationField(elem);

                } else if (elem.identifier === 'arrivalDate') {
                    elem.startDate = 'documentDate';
                    elem.endDate = '_TODAY';

                } else if (elem.identifier === 'initiator' && !this.adminMode) {
                    this.setInitiatorField(elem);

                } else if (elem.identifier === 'processLimitDate') {
                    elem.startDate = '_TODAY';
                    elem.endDate = '';
                    elem.event = 'setPriorityColorByLimitDate';

                } else if (elem.identifier === 'departureDate') {
                    elem.startDate = 'documentDate';
                    elem.endDate = '';

                } else if (elem.identifier === 'folders') {
                    elem.values = null;

                } else if (elem.identifier === 'category_id') {
                    await this.setCategoryField(elem);

                } else if (elem.identifier === 'priority') {
                    await this.setPriorityField(elem);

                } else if (elem.identifier === 'doctype') {
                    await this.setDoctypeField(elem);
                } else if (elem.identifier === 'registeredMail_type') {
                    elem.event = 'getIssuingSites';
                    // await this.setDoctypeField(elem);
                }
            }));
        }));

        if (this.resId !== null) {

            await this.setResource(saveResourceState);
        }

        this.loading = false;
    }

    setResource(saveResourceState: boolean = true) {
        return new Promise((resolve, reject) => {
            this.http.get(`../rest/resources/${this.resId}`).pipe(
                tap(async (data: any) => {
                    await Promise.all(this.fieldCategories.map(async (element: any) => {

                        // this.fieldCategories.forEach(async element => {
                        await Promise.all(this['indexingModels_' + element].map(async (elem: any) => {
                            // this['indexingModels_' + element].forEach((elem: any) => {
                            const customId: any = Object.keys(data.customFields).filter(index => index === elem.identifier.split('indexingCustomField_')[1])[0];

                            if (Object.keys(data).indexOf(elem.identifier) > -1 || customId !== undefined) {
                                let fieldValue: any = '';
                                if (customId !== undefined) {
                                    fieldValue = data.customFields[customId];
                                } else {
                                    fieldValue = data[elem.identifier];
                                }

                                if (elem.identifier === 'registeredMail_type') {
                                    this.getIssuingSites(null, fieldValue);
                                }

                                if (elem.identifier === 'priority') {
                                    this.setPriorityColor(null, fieldValue);
                                } else if (elem.identifier === 'processLimitDate' && !this.functions.empty(fieldValue)) {
                                    elem.startDate = '';
                                } else if (elem.identifier === 'destination') {
                                    if (this.mode === 'process') {
                                        this.arrFormControl[elem.identifier].disable();
                                    }
                                    this.arrFormControl['diffusionList'].disable();
                                } else if (elem.identifier === 'initiator' && elem.values.filter((val: any) => val.id === fieldValue).length === 0 && !this.functions.empty(fieldValue)) {
                                    await this.getCurrentInitiator(elem, fieldValue);
                                }

                                if (elem.type === 'date' && !this.functions.empty(fieldValue)) {
                                    fieldValue = new Date(fieldValue);
                                }
                                if (!this.functions.empty(fieldValue)) {
                                    this.arrFormControl[elem.identifier].setValue(fieldValue);
                                }
                            } else if (!saveResourceState && elem.identifier === 'destination') {
                                this.arrFormControl[elem.identifier].disable();
                                this.arrFormControl[elem.identifier].setValidators([]);
                                this.arrFormControl['diffusionList'].disable();
                            }

                            if (!this.canEdit) {
                                this.arrFormControl[elem.identifier].disable();
                            }
                        }));
                    }));
                    this.arrFormControl['mail­tracking'].setValue(data.followed);
                    if (saveResourceState) {
                        this.currentResourceValues = JSON.parse(JSON.stringify(this.getDatas(false)));
                    }
                    resolve(true);
                }),
                catchError((err: any) => {
                    this.notify.handleErrors(err);
                    return of(false);
                })
            ).subscribe();
        });
    }

    getCurrentInitiator(field: any, initiatorId: number) {
        return new Promise((resolve, reject) => {
            this.http.get(`../rest/entities/${initiatorId}`).pipe(
                tap((data: any) => {
                    field.values.unshift({
                        id: data.id,
                        label: data.entity_label
                    });
                    resolve(true);
                })
            ).subscribe();
        });
    }

    createForm() {
        this.indexingFormGroup = new FormGroup(this.arrFormControl);
        this.loadingFormEndEvent.emit();
    }

    async resetForm() {
        Object.keys(this.arrFormControl).forEach(element => {
            delete this.arrFormControl[element];
        });

        this.availableFields = JSON.parse(JSON.stringify(this.availableFieldsClone));
        this.fieldCategories.forEach(category => {
            this['indexingModels_' + category] = [];
        });

        if (this.availableCustomFieldsClone === null) {
            await this.initCustomFields();
        } else {
            this.availableCustomFields = JSON.parse(JSON.stringify(this.availableCustomFieldsClone));
        }
    }

    async loadForm(indexModelId: number, saveResourceState: boolean = true) {
        this.loading = true;

        this.customDiffusion = [];
        this.indexingFormId = indexModelId;

        await this.resetForm();

        if (!this.adminMode) {
            this.arrFormControl['mail­tracking'] = new FormControl({ value: '', disabled: this.adminMode ? true : false });
        }

        this.http.get(`../rest/indexingModels/${indexModelId}`).pipe(
            tap(async (data: any) => {
                this.indexingFormId = data.indexingModel.master !== null ? data.indexingModel.master : data.indexingModel.id;
                this.currentCategory = data.indexingModel.category;
                let fieldExist: boolean;
                if (data.indexingModel.fields.length === 0) {
                    this.initFields();
                    this.notify.error(this.translate.instant('lang.noFieldInModelMsg'));
                } else {
                    data.indexingModel.fields.forEach((field: any) => {
                        fieldExist = false;
                        field.system = false;
                        field.values = [];

                        let indexFound = this.availableFields.map(avField => avField.identifier).indexOf(field.identifier);

                        if (indexFound > -1) {
                            field.label = this.availableFields[indexFound].label;
                            field.default_value = !this.functions.empty(field.default_value) ? field.default_value : this.availableFields[indexFound].default_value;
                            field.values = this.availableFields[indexFound].values;
                            field.type = this.availableFields[indexFound].type;
                            this.availableFields.splice(indexFound, 1);
                            fieldExist = true;
                        }

                        indexFound = this.availableCustomFields.map(avField => avField.identifier).indexOf(field.identifier);

                        if (indexFound > -1) {
                            field.label = this.availableCustomFields[indexFound].label;
                            field.default_value = !this.functions.empty(field.default_value) ? field.default_value : this.availableCustomFields[indexFound].default_value;
                            field.values = this.availableCustomFields[indexFound].values;
                            field.type = this.availableCustomFields[indexFound].type;
                            field.SQLMode = this.availableCustomFields[indexFound].SQLMode;
                            this.availableCustomFields.splice(indexFound, 1);
                            fieldExist = true;
                        }

                        indexFound = this.indexingModelsCore.map(info => info.identifier).indexOf(field.identifier);

                        if (indexFound > -1) {
                            field.label = this.indexingModelsCore[indexFound].label;
                            field.default_value = !this.functions.empty(field.default_value) ? field.default_value : this.indexingModelsCore[indexFound].default_value;
                            field.values = this.indexingModelsCore[indexFound].values;
                            field.type = this.indexingModelsCore[indexFound].type;
                            fieldExist = true;
                            field.system = true;
                        }

                        if (field.type === 'date' && field.default_value === '_TODAY') {
                            field.today = true;
                            field.default_value = new Date();
                        }

                        if (field.identifier === 'initiator' && this.mode === 'indexation' && this.functions.empty(field.default_value)) {
                            if (this.headerService.user.entities[0]) {
                                field.default_value = this.headerService.user.entities.filter((entity: any) => entity.primary_entity === 'Y')[0].id;
                            }
                        }

                        if (field.identifier === 'diffusionList') {
                            this.customDiffusion = field.default_value;
                        }

                        if (fieldExist) {
                            this['indexingModels_' + field.unit].push(field);
                            this.initValidator(field);
                        } else if (field.identifier !== 'diffusionList') {
                            this.notify.error(this.translate.instant('lang.fieldNotExist') + ': ' + field.identifier);
                        }

                    });
                }

                await this.initElemForm(saveResourceState);
                this.createForm();

            }),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    enableField(field: any, enable: boolean) {
        if (enable) {
            if (!this.isAlwaysDisabledField(field)) {
                this.arrFormControl[field.identifier].enable();
            }
            field.enabled = true;
        } else {
            if (this.functions.empty(this.arrFormControl[field.identifier].value) && field.mandatory) {
                alert(this.translate.instant('lang.canNotDisabledField'));
                return false;
            }
            this.arrFormControl[field.identifier].disable();
            field.enabled = false;
        }
    }

    isAlwaysDisabledField(field: any) {
        if (this.adminMode && ((['integer', 'string', 'date'].indexOf(field.type) > -1 && !this.functions.empty(field.values)) || field.today)) {
            return true;
        }
        return false;
    }

    initValidator(field: any) {
        let valArr: ValidatorFn[] = [];

        const disabledState = !field.enabled || this.isAlwaysDisabledField(field);
        if (!disabledState) {
            field.enabled = true;
        }

        this.arrFormControl[field.identifier] = new FormControl({ value: field.default_value, disabled: disabledState });

        if (field.type === 'integer') {
            valArr.push(this.regexValidator(new RegExp('[+-]?([0-9]*[.])?[0-9]+'), { 'floatNumber': '' }));
        } else if (field.type === 'date' && !this.functions.empty(field.default_value)) {
            this.arrFormControl[field.identifier].setValue(new Date(field.default_value));
        }

        if (field.mandatory && !this.adminMode) {
            valArr.push(Validators.required);
        }

        this.arrFormControl[field.identifier].setValidators(valArr);

        if (field.identifier === 'destination') {
            let valArr: ValidatorFn[] = [];
            if (field.mandatory) {
                valArr.push(Validators.required);
                valArr.push(this.requireDestValidator({ 'isDest': '' }));
            } else {
                valArr.push(this.requireDestValidatorOrEmpty({ 'isDest': '' }));
            }

            this.arrFormControl['diffusionList'] = new FormControl({ value: null, disabled: false });

            this.arrFormControl['diffusionList'].setValidators(valArr);

            this.arrFormControl['diffusionList'].setValue([]);

        }
    }

    requireDestValidator(error: ValidationErrors): ValidatorFn {
        return (control: AbstractControl): { [key: string]: any } => {
            if (!control.value) {
                return null;
            }
            return control.value.filter((item: any) => item.mode === 'dest').length > 0 ? null : error;
        };
    }

    requireDestValidatorOrEmpty(error: ValidationErrors): ValidatorFn {
        return (control: AbstractControl): { [key: string]: any } => {
            if (!control.value) {
                return null;
            }
            return control.value.filter((item: any) => item.mode === 'dest').length > 0 || this.functions.empty(this.arrFormControl['destination'].value) ? null : error;
        };
    }

    regexValidator(regex: RegExp, error: ValidationErrors): ValidatorFn {
        return (control: AbstractControl): { [key: string]: any } => {
            if (!control.value) {
                return null;
            }
            const valid = regex.test(control.value);
            return valid ? null : error;
        };
    }

    isValidForm() {
        if (!this.indexingFormGroup.valid) {
            Object.keys(this.indexingFormGroup.controls).forEach(key => {

                const controlErrors: ValidationErrors = this.indexingFormGroup.get(key).errors;
                if (controlErrors != null) {
                    this.indexingFormGroup.controls[key].markAsTouched();
                }
            });
        }
        return this.indexingFormGroup.valid;
    }

    isEmptyField(field: any) {

        if (this.arrFormControl[field.identifier].value === null) {
            return true;

        } else if (Array.isArray(this.arrFormControl[field.identifier].value)) {
            if (this.arrFormControl[field.identifier].value.length > 0) {
                return false;
            } else {
                return true;
            }
        } else if (String(this.arrFormControl[field.identifier].value) !== '') {
            return false;
        } else {
            return true;
        }
    }

    getMinDate(id: string) {
        if (this.arrFormControl[id] !== undefined) {
            return this.arrFormControl[id].value;
        } else if (id === '_TODAY') {
            return new Date();
        } else {
            return '';
        }
    }

    getMaxDate(id: string) {
        if (this.arrFormControl[id] !== undefined) {
            return this.arrFormControl[id].value;
        } else if (id === '_TODAY') {
            return new Date();
        } else {
            return '';
        }
    }

    toggleTodayDate(field: any) {
        field.today = !field.today;
        if (field.today) {
            this.arrFormControl[field.identifier].disable();
            this.arrFormControl[field.identifier].setValue(new Date());
        } else {
            this.arrFormControl[field.identifier].setValue('');
            this.arrFormControl[field.identifier].enable();
        }
    }

    toggleMailTracking() {
        this.arrFormControl['mail­tracking'].setValue(!this.arrFormControl['mail­tracking'].value);
    }

    changeCategory(categoryId: string) {
        this.currentCategory = categoryId;
        this.changeRegisteredMailItems(categoryId);
    }

    changeDestination(entityIds: number[], allowedEntities: number[]) {

        if (entityIds.indexOf(this.arrFormControl['destination'].value) === -1) {
            this.arrFormControl['destination'].setValue(entityIds[0]);
        }
    }

    launchEvent(value: any, field: any) {
        if (field.event !== undefined && value !== null && !this.adminMode) {
            this[field.event](field, value);
        }
    }

    calcLimitDate(field: any, value: any) {
        let limitDate: any = null;
        if (this.arrFormControl['processLimitDate'] !== undefined) {
            this.http.get('../rest/indexing/processLimitDate', { params: { 'doctype': value } }).pipe(
                tap((data: any) => {
                    limitDate = data.processLimitDate !== null ? new Date(data.processLimitDate) : '';
                    this.arrFormControl['processLimitDate'].setValue(limitDate);
                }),
                filter((data) => this.arrFormControl['priority'] !== undefined && data.processLimitDate !== null),
                exhaustMap(() => this.http.get('../rest/indexing/priority', { params: { 'processLimitDate': limitDate.toDateString() } })),
                tap((data: any) => {
                    this.arrFormControl['priority'].setValue(data.priority);
                    this.setPriorityColor(null, data.priority);

                }),
                catchError((err: any) => {
                    this.notify.handleErrors(err);
                    return of(false);
                })
            ).subscribe();
        }
    }

    calcLimitDateByPriority(field: any, value: any) {
        let limitDate: any = null;

        if (this.arrFormControl['processLimitDate'] !== undefined) {
            this.http.get('../rest/indexing/processLimitDate', { params: { 'priority': value } }).pipe(
                tap((data: any) => {
                    limitDate = data.processLimitDate !== null ? new Date(data.processLimitDate) : '';
                    this.arrFormControl['processLimitDate'].setValue(limitDate);
                    this.setPriorityColor(field, value);
                }),
                catchError((err: any) => {
                    this.notify.handleErrors(err);
                    return of(false);
                })
            ).subscribe();
        } else {
            this.setPriorityColor(field, value);
        }
    }

    setPriorityColor(field: any, value: any) {
        if (field !== null) {
            this.currentPriorityColor = field.values.filter((fieldVal: any) => fieldVal.id === value).map((fieldVal: any) => fieldVal.color)[0];
        } else {
            this.fieldCategories.forEach(element => {
                if (this['indexingModels_' + element].filter((field: any) => field.identifier === 'priority').length > 0) {
                    this.currentPriorityColor = this['indexingModels_' + element].filter((field: any) => field.identifier === 'priority')[0].values.filter((fieldVal: any) => fieldVal.id === value).map((fieldVal: any) => fieldVal.color)[0]
                }
            });
        }

    }

    setPriorityColorByLimitDate(field: any, value: any) {

        const limitDate = new Date(value.value);

        this.http.get('../rest/indexing/priority', { params: { 'processLimitDate': limitDate.toDateString() } }).pipe(
            tap((data: any) => {
                this.arrFormControl['priority'].setValue(data.priority);
                this.setPriorityColor(null, data.priority);
            }),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();

    }

    loadDiffusionList(field: any, value: any) {
        if (!this.functions.empty(this.appDiffusionsList)) {
            this.appDiffusionsList.loadListModel(value);
        }
    }

    getCheckboxListLabel(selectedItemId: any, items: any) {
        return items.filter((item: any) => item.id === selectedItemId)[0].label;
    }

    /**
     * [Registered mail module]
     */
    getIssuingSites(field: any, value: any) {
        this.fieldCategories.forEach(element => {
            this['indexingModels_' + element].forEach((fieldItem: any) => {
                if (fieldItem.identifier === 'registeredMail_warranty') {
                    fieldItem.values[2].disabled = value === 'RW';
                }
            });
            if (value === 'RW' && this.arrFormControl['registeredMail_warranty'].value === 'R3') {
                this.arrFormControl['registeredMail_warranty'].setValue('R1');
            }
        });
        if (!this.functions.empty(this.appIssuingSiteInput)) {
            this.appIssuingSiteInput.registedMailType = value;
        }
    }

    /**
     * [Registered mail module]
     */
    transferRegisteredMailInput(event: CdkDragDrop<string[]>) {
        const regex = /registeredMail_[.]*/g;

        this.transferInput(event);
        if (event.item.data.identifier !== 'registeredMail_type') {
            const obj = event.previousContainer.data.map((item: any, indexItem: number) => ({ index: indexItem, identifier: item.identifier })).filter((item: any) => item.identifier === 'registeredMail_type')[0];

            this.initValidator(event.previousContainer.data[obj.index]);

            event.previousContainer.data[obj.index]['unit'] = event.container.id.split('_')[1];
            event.container.data.splice(event.currentIndex, 0, event.previousContainer.data[obj.index]);
            event.previousContainer.data.splice(obj.index, 1);
        }

        event.previousContainer.data.forEach((item: any, indexData: number) => {
            if (item.identifier.match(regex) !== null) {
                this.initValidator(item);
                item.unit = event.container.id.split('_')[1];
                event.container.data.splice(event.currentIndex, 0, item);
                event.previousContainer.data.splice(indexData, 1);
            }
        });
        this.initElemForm();
    }

    /**
     * [Registered mail module]
     */
    changeRegisteredMailItems(categoryId: string) {
        if (categoryId !== 'registeredMail') {
            this.fieldCategories.forEach(category => {
                this.availableFields = this.availableFields.concat(this['indexingModels_' + category].filter((item: any) => item.identifier.indexOf('registeredMail_') > -1));
                this['indexingModels_' + category] = this['indexingModels_' + category].filter((item: any) => item.identifier.indexOf('registeredMail_') === -1);
            });
        } else {
            this['indexingModels_mail'] = this['indexingModels_mail'].concat(this.availableFields.filter((field: any) => field.identifier.indexOf('registeredMail_') > -1 || field.identifier === 'departureDate'));
            this['indexingModels_mail'].forEach((item: any) => {
                if (item.identifier.indexOf('registeredMail_') > -1 || item.identifier === 'departureDate') {
                    if (this.functions.empty(item.unit)) {
                        item.unit = 'mail';
                    }
                    if (item.identifier !== 'registeredMail_number') {
                        item.mandatory = true;
                    }

                    this.initValidator(item);
                }
            });
            this.availableFields = this.availableFields.filter((item: any) => item.identifier.indexOf('registeredMail_') === -1 && item.identifier !== 'departureDate');
        }
    }
}
