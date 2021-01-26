import { Component, OnInit, Input, ViewChild, ElementRef, Output, EventEmitter } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { AppService } from '@service/app.service';
import { SortPipe } from '../../../plugins/sorting.pipe';
import { FormControl } from '@angular/forms';
import { Observable, of } from 'rxjs';
import { debounceTime, filter, distinctUntilChanged, tap, switchMap, exhaustMap, catchError, finalize, map } from 'rxjs/operators';
import { LatinisePipe } from 'ngx-pipes';
import { PrivilegeService } from '@service/privileges.service';
import { ContactModalComponent } from '../../administration/contact/modal/contact-modal.component';
import { ContactService } from '@service/contact.service';
import { DocumentViewerComponent } from '../../viewer/document-viewer.component';
import { FunctionsService } from '@service/functions.service';

@Component({
    selector: 'app-contact-autocomplete',
    templateUrl: 'contact-autocomplete.component.html',
    styleUrls: [
        'contact-autocomplete.component.scss',
        '../../indexation/indexing-form/indexing-form.component.scss'
    ],
    providers: [SortPipe, ContactService]
})

export class ContactAutocompleteComponent implements OnInit {

    loading: boolean = false;
    loadingValues: boolean = true;

    key: string = 'id';

    canAdd: boolean = false;
    canUpdate: boolean = false;

    noResultFound: boolean = null;

    listInfo: string;
    myControl = new FormControl();
    filteredOptions: Observable<string[]>;
    options: any;
    valuesToDisplay: any = {};
    dialogRef: MatDialogRef<any>;
    newIds: number[] = [];
    customFields: any[] = [];


    /**
     * FormControl used when autocomplete is used in form and must be catched in a form control.
     */
    @Input('control') controlAutocomplete: FormControl;

    @Input() id: string = 'contact-autocomplete';
    @Input() exclusion: string = '';

    @Input() singleMode: boolean = false;

    @Output() retrieveDocumentEvent = new EventEmitter<string>();

    @ViewChild('autoCompleteInput', { static: true }) autoCompleteInput: ElementRef;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        public dialog: MatDialog,
        private headerService: HeaderService,
        public appService: AppService,
        private latinisePipe: LatinisePipe,
        private privilegeService: PrivilegeService,
        private contactService: ContactService,
        public functions: FunctionsService
    ) {

    }

    ngOnInit() {
        this.controlAutocomplete.setValue(this.controlAutocomplete.value === null || this.controlAutocomplete.value === '' ? [] : this.controlAutocomplete.value);
        this.canAdd = this.privilegeService.hasCurrentUserPrivilege('create_contacts');
        this.canUpdate = this.privilegeService.hasCurrentUserPrivilege('update_contacts');
        this.getCustomFields();
        this.initFormValue();
        this.initAutocompleteRoute();
    }

    initAutocompleteRoute() {
        this.listInfo = this.translate.instant('lang.autocompleteInfo');
        this.options = [];
        this.myControl.valueChanges
            .pipe(
                tap(() => {
                    this.noResultFound = null;
                    this.options = [];
                    this.listInfo = this.translate.instant('lang.autocompleteInfo');
                }),
                debounceTime(300),
                filter(value => value.length > 2),
                // distinctUntilChanged(),
                tap(() => this.loading = true),
                switchMap((data: any) => this.getDatas(data)),
                map((data: any) => {
                    data = data.filter((contact: any) => !this.singleMode || (contact.type !== 'entity' && contact.type !== 'contactGroup' && this.singleMode));
                    data = data.map((contact: any) => {
                        return {
                            ...contact,
                            civility: this.contactService.formatCivilityObject(contact.civility),
                            fillingRate: this.contactService.formatFillingObject(contact.fillingRate),
                            customFields: contact.customFields !== undefined ? this.formatCustomField(contact.customFields) : [],
                        };
                    });
                    return data;
                }),
                tap((data: any) => {
                    if (data.length === 0) {
                        this.noResultFound = true;
                        this.listInfo = this.translate.instant('lang.noAvailableValue');
                    } else {
                        this.noResultFound = false;
                        this.listInfo = '';
                    }
                    this.options = data;
                    this.filteredOptions = of(this.options);
                    this.loading = false;
                })
            ).subscribe();
    }

    getCustomFields() {
        this.http.get('../rest/contactsCustomFields').pipe(
            tap((data: any) => {
                this.customFields = data.customFields.map((custom: any) => {
                    return {
                        id: custom.id,
                        label: custom.label
                    };
                });
            })
        ).subscribe();
    }

    formatCustomField(data: any) {
        const arrCustomFields: any[] = [];

        Object.keys(data).forEach(element => {
            arrCustomFields.push({
                label: this.customFields.filter(custom => custom.id == element)[0].label,
                value: data[element]
            });
        });

        return arrCustomFields;
    }

    getDatas(data: string) {
        return this.http.get('../rest/autocomplete/correspondents' + this.exclusion, { params: { 'search': data } });
    }

    selectOpt(ev: any) {
        this.setFormValue(ev.option.value);
        this.myControl.setValue('');

    }

    initFormValue() {
        this.controlAutocomplete.value.forEach((contact: any) => {
            this.valuesToDisplay[contact.id] = {
                type: '',
                firstname: '',
                lastname: this.translate.instant('lang.undefined'),
                company: '',
                fillingRate: {
                    color: ''
                }
            };

            if (contact.type === 'contact') {
                this.http.get('../rest/contacts/' + contact.id).pipe(
                    tap((data: any) => {
                        this.valuesToDisplay[data.id] = {
                            type: 'contact',
                            firstname: data.firstname,
                            lastname: data.lastname,
                            company: data.company,
                            fillingRate: !this.functions.empty(data.fillingRate) ? {
                                color: this.contactService.getFillingColor(data.fillingRate.thresholdLevel)
                            } : ''
                        };
                    }),
                    finalize(() => this.loadingValues = false),
                    catchError((err: any) => {
                        this.notify.error(err.error.errors);
                        return of(false);
                    })
                ).subscribe();
            } else if (contact.type === 'user') {
                this.http.get('../rest/users/' + contact.id).pipe(
                    tap((data: any) => {
                        this.valuesToDisplay[data.id] = {
                            type: 'user',
                            firstname: data.firstname,
                            lastname: data.lastname,
                            fillingRate: {
                                color: ''
                            }
                        };
                    }),
                    finalize(() => this.loadingValues = false),
                    catchError((err: any) => {
                        this.notify.error(err.error.errors);
                        return of(false);
                    })
                ).subscribe();
            } else if (contact.type === 'entity') {
                this.http.get('../rest/entities/' + contact.id).pipe(
                    tap((data: any) => {
                        this.valuesToDisplay[data.id] = {
                            type: 'entity',
                            lastname: data.entity_label,
                            fillingRate: {
                                color: ''
                            }
                        };
                    }),
                    finalize(() => this.loadingValues = false),
                    catchError((err: any) => {
                        this.notify.error(err.error.errors);
                        return of(false);
                    })
                ).subscribe();
            }
        });
    }

    setFormValue(item: any) {
        if (item.type === 'contactGroup') {
            this.http.get('../rest/contactsGroups/' + item.id).pipe(
                map((data: any) => {
                    const contacts = data.contactsGroup.contacts.map((contact: any) => {
                        return {
                            id: contact.id,
                            type: contact.type,
                            lastname: contact.contact,
                            fillingRate: !this.functions.empty(contact.thresholdLevel) ? {
                                color: this.contactService.getFillingColor(contact.thresholdLevel)
                            } : ''
                        };
                    });
                    return contacts;
                }),
                tap((contacts: any) => {
                    contacts.forEach((contact: any) => {
                        this.setContact(contact);
                    });
                }),
                finalize(() => this.loadingValues = false),
                catchError((err: any) => {
                    this.notify.error(err.error.errors);
                    return of(false);
                })
            ).subscribe();
        } else {
            this.setContact(item);
        }

    }

    setContact(contact: any) {
        if (this.controlAutocomplete.value.map((contactItem: any) => contactItem.id).indexOf(contact['id']) === -1) {
            let arrvalue = [];
            if (this.controlAutocomplete.value !== null) {
                arrvalue = this.controlAutocomplete.value;
            }
            this.valuesToDisplay[contact['id']] = contact;
            arrvalue.push(
                {
                    type: contact['type'],
                    id: contact['id'],
                    label: this.getFormatedContact(contact['id'])
                });
            this.controlAutocomplete.setValue(arrvalue);
            this.loadingValues = false;
        }
    }

    resetAutocomplete() {
        this.options = [];
        this.listInfo = this.translate.instant('lang.autocompleteInfo');
        this.myControl.setValue('');
    }

    private _filter(value: string): string[] {
        if (typeof value === 'string') {
            const filterValue = this.latinisePipe.transform(value.toLowerCase());
            return this.options.filter((option: any) => this.latinisePipe.transform(option[this.key].toLowerCase()).includes(filterValue));
        } else {
            return this.options;
        }
    }

    unsetValue() {
        this.controlAutocomplete.setValue('');
        this.myControl.setValue('');
        this.myControl.enable();
    }

    removeItem(index: number) {

        if (this.newIds.indexOf(this.controlAutocomplete.value[index]) === -1) {
            const arrValue = this.controlAutocomplete.value;
            this.controlAutocomplete.value.splice(index, 1);
            this.controlAutocomplete.setValue(arrValue);
        } else {
            this.http.delete('../rest/tags/' + this.controlAutocomplete.value[index]).pipe(
                tap((data: any) => {
                    const arrValue = this.controlAutocomplete.value;
                    this.controlAutocomplete.value.splice(index, 1);
                    this.controlAutocomplete.setValue(arrValue);
                }),
                catchError((err: any) => {
                    this.notify.handleErrors(err);
                    return of(false);
                })
            ).subscribe();
        }
    }

    openContact(contact: any = null) {
        this.retrieveDocumentEvent.emit();
        const dialogRef = this.dialog.open(
            ContactModalComponent,
            {
                maxWidth: '100vw',
                width: contact === null ? '99vw' : 'auto',
                panelClass: contact === null ? 'maarch-full-height-modal' : 'maarch-modal',
                disableClose: true,
                data: {
                    editMode: this.canUpdate,
                    contactId: contact !== null ? contact.id : null,
                    contactType: contact !== null ? contact.type : null }
            }
        );

        dialogRef.afterClosed().pipe(
            filter((data: number) => data !== undefined),
            tap((contactId: number) => {
                const newContact = {
                    type: 'contact',
                    id: contactId
                };
                this.setFormValue(newContact);
                this.initFormValue();
            }),
            catchError((err: any) => {
                this.notify.handleErrors(err);
                return of(false);
            })
        ).subscribe();
    }

    empty(value: any) {
        if (value !== null && value !== '' && value !== undefined) {
            return false;
        } else {
            return true;
        }
    }

    resetAll() {
        this.controlAutocomplete.setValue([]);
        this.valuesToDisplay = {};
    }

    getFormatedContact(id: number) {
        return this.contactService.formatContact(this.valuesToDisplay[id]);
    }

    getInputValue() {
        return this.myControl.value;
    }

    setInputValue(value: string) {
        this.myControl.setValue(value);
    }

    resetInputValue() {
        this.myControl.setValue('');
    }
}
