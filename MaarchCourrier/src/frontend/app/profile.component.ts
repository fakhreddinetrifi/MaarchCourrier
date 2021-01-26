import { Component, OnInit, NgZone, ViewChild, QueryList, ViewChildren, TemplateRef, ViewContainerRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { debounceTime, switchMap, distinctUntilChanged, filter, tap } from 'rxjs/operators';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { MatExpansionPanel } from '@angular/material/expansion';
import { MatPaginator } from '@angular/material/paginator';
import { MatSidenav } from '@angular/material/sidenav';
import { MatSort } from '@angular/material/sort';
import { MatTableDataSource } from '@angular/material/table';
import { SelectionModel } from '@angular/cdk/collections';
import { FormControl, FormGroup, Validators, AbstractControl, ValidationErrors, ValidatorFn, FormBuilder } from '@angular/forms';
import { AppService } from '@service/app.service';
import { FunctionsService } from '@service/functions.service';
import { AuthService } from '@service/auth.service';
import { Router } from '@angular/router';

declare var $: any;
declare var tinymce: any;


@Component({
    templateUrl: 'profile.component.html',
    styleUrls: ['profile.component.css']
})
export class ProfileComponent implements OnInit {

    dialogRef: MatDialogRef<any>;

    highlightMe: boolean = false;
    user: any = {
        baskets: []
    };
    histories: any[] = [];
    passwordModel: any = {
        currentPassword: '',
        newPassword: '',
        reNewPassword: '',
    };
    firstFormGroup: FormGroup;
    ruleText: string = '';
    otherRuleText: string;
    validPassword: Boolean = false;
    matchPassword: Boolean = false;
    hidePassword: Boolean = true;
    passwordRules: any = {
        minLength: { enabled: false, value: 0 },
        complexityUpper: { enabled: false, value: 0 },
        complexityNumber: { enabled: false, value: 0 },
        complexitySpecial: { enabled: false, value: 0 },
        renewal: { enabled: false, value: 0 },
        historyLastUse: { enabled: false, value: 0 },
    };
    signatureModel: any = {
        base64: '',
        base64ForJs: '',
        name: '',
        type: '',
        size: 0,
        label: '',
    };
    mailSignatureModel: any = {
        selected: -1,
        htmlBody: '',
        title: '',
    };
    userAbsenceModel: any[] = [];
    basketsToRedirect: string[] = [];

    showPassword: boolean = false;
    selectedSignature: number = -1;
    selectedSignatureLabel: string = '';
    loading: boolean = false;
    selectedIndex: number = 0;
    selectedIndexContactsGrp: number = 0;
    loadingSign: boolean = false;

    @ViewChild('snav2', { static: true }) sidenavRight: MatSidenav;
    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;

    // Redirect Baskets
    selectionBaskets = new SelectionModel<Element>(true, []);
    myBasketExpansionPanel: boolean = false;
    editorsList: any;
    masterToggleBaskets(event: any) {
        if (event.checked) {
            this.user.baskets.forEach((basket: any) => {
                if (!basket.userToDisplay) {
                    this.selectionBaskets.select(basket);
                }
            });
        } else {
            this.selectionBaskets.clear();
        }
    }

    @ViewChildren(MatExpansionPanel) viewPanels: QueryList<MatExpansionPanel>;

    // Groups contacts
    contactsGroups: any[] = [];
    displayedColumnsGroupsList: string[] = ['label', 'description', 'nbContacts', 'public', 'actions'];
    dataSourceGroupsList: any;
    @ViewChild('paginatorGroupsList', { static: false }) paginatorGroupsList: MatPaginator;
    @ViewChild('tableGroupsListSort', { static: false }) sortGroupsList: MatSort;
    applyFilterGroupsList(filterValue: string) {
        filterValue = filterValue.trim(); // Remove whitespace
        filterValue = filterValue.toLowerCase(); // MatTableDataSource defaults to lowercase matches
        this.dataSourceGroupsList.filter = filterValue;
    }

    // Group contacts
    contactsGroup: any = { public: false };

    // Group contacts List Autocomplete
    initAutoCompleteContact = true;

    searchTerm: FormControl = new FormControl();
    searchResult: any = [];
    displayedColumnsContactsListAutocomplete: string[] = ['select', 'contact', 'address'];
    dataSourceContactsListAutocomplete: any;
    @ViewChild('paginatorGroupsListAutocomplete', { static: false }) paginatorGroupsListAutocomplete: MatPaginator;
    selection = new SelectionModel<Element>(true, []);
    masterToggle(event: any) {
        if (event.checked) {
            this.dataSourceContactsListAutocomplete.data.forEach((row: any) => {
                if (!$('#check_' + row.id + '-input').is(':disabled')) {
                    this.selection.select(row.id);
                }
            });
        } else {
            this.selection.clear();
        }
    }


    // Group contacts List
    contactsListMode: boolean = false;
    contactsList: any[] = [];
    displayedColumnsContactsList: string[] = ['contact', 'address', 'actions'];
    dataSourceContactsList: any;
    @ViewChild('paginatorContactsList', { static: false }) paginatorContactsList: MatPaginator;
    @ViewChild('tableContactsListSort', { static: false }) sortContactsList: MatSort;
    applyFilterContactsList(filterValue: string) {
        filterValue = filterValue.trim(); // Remove whitespace
        filterValue = filterValue.toLowerCase(); // MatTableDataSource defaults to lowercase matches
        this.dataSourceContactsList.filter = filterValue;
    }

    // History
    displayedColumns = ['event_date', 'record_id', 'info'];
    dataSource: any;
    @ViewChild('paginatorHistory', { static: false }) paginatorHistory: MatPaginator;
    @ViewChild('tableHistorySort', { static: false }) sortHistory: MatSort;
    applyFilter(filterValue: string) {
        filterValue = filterValue.trim(); // Remove whitespace
        filterValue = filterValue.toLowerCase(); // MatTableDataSource defaults to lowercase matches
        this.dataSource.filter = filterValue;
    }


    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private zone: NgZone,
        private notify: NotificationService,
        public dialog: MatDialog,
        private _formBuilder: FormBuilder,
        private authService: AuthService,
        public headerService: HeaderService,
        public appService: AppService,
        private viewContainerRef: ViewContainerRef,
        private functions: FunctionsService
    ) {
        window['angularProfileComponent'] = {
            componentAfterUpload: (base64Content: any) => this.processAfterUpload(base64Content),
        };
        this.searchTerm.valueChanges.pipe(
            debounceTime(500),
            filter(value => value.length > 2),
            distinctUntilChanged(),
            switchMap(data => this.http.get('../rest/autocomplete/contacts', { params: { 'search': data } }))
        ).subscribe((response: any) => {
            this.searchResult = response;
            this.dataSourceContactsListAutocomplete = new MatTableDataSource(this.searchResult);
            this.dataSourceContactsListAutocomplete.paginator = this.paginatorGroupsListAutocomplete;
            // this.dataSource.sort      = this.sortContactList;
        });

        this.http.get('../rest/documentEditors').pipe(
            tap((data: any) => {
                this.editorsList = data;
            })
        ).subscribe();

    }

    initComponents(event: any) {
        this.selectedIndex = event.index;
        if (event.index == 2) {
            if (!this.appService.getViewMode()) {
                this.sidenavRight.open();
            }

            this.http.get('../rest/history/users/' + this.user.id)
                .subscribe((data: any) => {
                    this.histories = data.histories;
                    setTimeout(() => {
                        this.dataSource = new MatTableDataSource(this.histories);
                        this.dataSource.sortingDataAccessor = this.functions.listSortingDataAccessor;
                        this.dataSource.paginator = this.paginatorHistory;
                        this.dataSource.sort = this.sortHistory;
                    }, 0);
                }, (err) => {
                    this.notify.error(err.error.errors);
                });

        } else if (event.index == 1) {
            this.sidenavRight.close();
        } else if (!this.appService.getViewMode()) {
            this.sidenavRight.open();
        }
    }

    initMce() {
        tinymce.remove('textarea');
        // LOAD EDITOR TINYMCE for MAIL SIGN
        tinymce.baseURL = '../node_modules/tinymce';
        tinymce.suffix = '.min';
        tinymce.init({
            selector: 'textarea#emailSignature',
            statusbar: false,
            language: this.translate.instant('lang.langISO').replace('-', '_'),
            language_url: `../node_modules/tinymce-i18n/langs/${this.translate.instant('lang.langISO').replace('-', '_')}.js`,
            height: '200',
            plugins: [
                'textcolor'
            ],
            external_plugins: {
                'maarch_b64image': '../../src/frontend/plugins/tinymce/maarch_b64image/plugin.min.js'
            },
            menubar: false,
            toolbar: 'undo | bold italic underline | alignleft aligncenter alignright | maarch_b64image | forecolor',
            theme_buttons1_add: 'fontselect,fontsizeselect',
            theme_buttons2_add_before: 'cut,copy,paste,pastetext,pasteword,separator,search,replace,separator',
            theme_buttons2_add: 'separator,insertdate,inserttime,preview,separator,forecolor,backcolor',
            theme_buttons3_add_before: 'tablecontrols,separator',
            theme_buttons3_add: 'separator,print,separator,ltr,rtl,separator,fullscreen,separator,insertlayer,moveforward,movebackward,absolut',
            theme_toolbar_align: 'left',
            theme_advanced_toolbar_location: 'top',
            theme_styles: 'Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1'

        });
    }

    initGroupsContact() {
        this.contactsListMode = false;
        this.selectedIndexContactsGrp = 0;
        this.http.get('../rest/contactsGroups')
            .subscribe((data) => {
                this.contactsGroups = [];
                this.contactsGroup = { public: false, contacts: [] };
                let i = 0;
                data['contactsGroups'].forEach((ct: any) => {
                    if (ct.owner == this.headerService.user.id) {
                        ct.position = i;
                        this.contactsGroups.push(ct);
                        i++;
                    }
                });
                setTimeout(() => {
                    this.dataSourceGroupsList = new MatTableDataSource(this.contactsGroups);
                    this.dataSourceGroupsList.paginator = this.paginatorGroupsList;
                    this.dataSourceGroupsList.sort = this.sortGroupsList;
                }, 0);
            }, (err) => {
                this.notify.handleErrors(err);
            });
    }

    contactsGroupSubmit() {
        this.http.post('../rest/contactsGroups', this.contactsGroup)
            .subscribe((data: any) => {
                this.initGroupsContact();
                // this.toggleAddGrp();
                this.notify.success(this.translate.instant('lang.contactsGroupAdded'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    updateGroupSubmit() {
        this.http.put('../rest/contactsGroups/' + this.contactsGroup.id, this.contactsGroup)
            .subscribe(() => {
                this.notify.success(this.translate.instant('lang.contactsGroupUpdated'));
                this.initGroupsContact();
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    deleteContactsGroup(row: any) {
        var contactsGroup = this.contactsGroups[row];
        let r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.delete') + ' « ' + contactsGroup.label + ' »');
        if (r) {
            this.http.delete('../rest/contactsGroups/' + contactsGroup.id)
                .subscribe(() => {
                    this.contactsListMode = false;
                    var lastElement = this.contactsGroups.length - 1;
                    this.contactsGroups[row] = this.contactsGroups[lastElement];
                    this.contactsGroups[row].position = row;
                    this.contactsGroups.splice(lastElement, 1);

                    this.dataSourceGroupsList = new MatTableDataSource(this.contactsGroups);
                    this.dataSourceGroupsList.paginator = this.paginatorGroupsList;
                    this.notify.success(this.translate.instant('lang.contactsGroupDeleted'));

                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    loadContactsGroup(contactsGroup: any) {
        this.contactsListMode = true;

        this.http.get('../rest/contactsGroups/' + contactsGroup.id)
            .subscribe((data: any) => {
                this.contactsGroup = data.contactsGroup;
                setTimeout(() => {
                    this.dataSourceContactsList = new MatTableDataSource(this.contactsGroup.contacts);
                    this.dataSourceContactsList.paginator = this.paginatorContactsList;
                    this.dataSourceContactsList.sort = this.sortContactsList;
                    this.selectedIndexContactsGrp = 1;
                }, 0);
            });
    }

    saveContactsList(elem: any): void {
        elem.textContent = this.translate.instant('lang.loading') + '...';
        elem.disabled = true;
        this.http.post('../rest/contactsGroups/' + this.contactsGroup.id + '/contacts', { 'contacts': this.selection.selected })
            .subscribe((data: any) => {
                this.notify.success(this.translate.instant('lang.contactAdded'));
                this.selection.clear();
                elem.textContent = this.translate.instant('lang.add');
                this.contactsGroup = data.contactsGroup;
                setTimeout(() => {
                    this.dataSourceContactsList = new MatTableDataSource(this.contactsGroup.contacts);
                    this.dataSourceContactsList.paginator = this.paginatorContactsList;
                    this.dataSourceContactsList.sort = this.sortContactsList;
                }, 0);
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    preDelete(index: number) {
        let r = confirm(this.translate.instant('lang.reallyWantToDeleteContactFromGroup'));

        if (r) {
            this.removeContact(this.contactsGroup.contacts[index], index);
        }
    }

    removeContact(contact: any, row: any) {
        this.http.delete('../rest/contactsGroups/' + this.contactsGroup.id + '/contacts/' + contact['id'])
            .subscribe(() => {
                var lastElement = this.contactsGroup.contacts.length - 1;
                this.contactsGroup.contacts[row] = this.contactsGroup.contacts[lastElement];
                this.contactsGroup.contacts[row].position = row;
                this.contactsGroup.contacts.splice(lastElement, 1);

                this.dataSourceContactsList = new MatTableDataSource(this.contactsGroup.contacts);
                this.dataSourceContactsList.paginator = this.paginatorContactsList;
                this.dataSourceContactsList.sort = this.sortContactsList;
                this.notify.success(this.translate.instant('lang.contactDeletedFromGroup'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    launchLoading() {
        if (this.searchTerm.value.length > 2) {
            this.dataSourceContactsListAutocomplete = null;
            this.initAutoCompleteContact = false;
        }
    }

    isInGrp(contact: any): boolean {
        let isInGrp = false;
        this.contactsGroup.contacts.forEach((row: any) => {
            if (row.id == contact.id) {
                isInGrp = true;
            }
        });
        return isInGrp;
    }

    selectContact(id: any) {
        if (!$('#check_' + id + '-input').is(':disabled')) {
            this.selection.toggle(id);
        }
    }

    ngOnInit(): void {
        this.headerService.setHeader(this.translate.instant('lang.myProfile'));
        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');

        this.loading = true;

        this.http.get('../rest/currentUser/profile')
            .subscribe((data: any) => {
                this.user = data;

                this.user.baskets.forEach((value: any, index: number) => {
                    this.user.baskets[index]['disabled'] = false;
                    this.user.redirectedBaskets.forEach((value2: any) => {
                        if (value.basket_id == value2.basket_id && value.basket_owner == value2.basket_owner) {
                            this.user.baskets[index]['disabled'] = true;
                        }
                    });
                });
                this.loading = false;
            });
    }

    processAfterUpload(b64Content: any) {
        this.zone.run(() => this.resfreshUpload(b64Content));
    }

    resfreshUpload(b64Content: any) {
        if (this.signatureModel.size <= 2000000) {
            this.signatureModel.base64 = b64Content.replace(/^data:.*?;base64,/, '');
            this.signatureModel.base64ForJs = b64Content;
        } else {
            this.signatureModel.name = '';
            this.signatureModel.size = 0;
            this.signatureModel.type = '';
            this.signatureModel.base64 = '';
            this.signatureModel.base64ForJs = '';

            this.notify.error('Taille maximum de fichier dépassée (2 MB)');
        }
    }

    displayPassword() {
        this.showPassword = !this.showPassword;
    }

    clickOnUploader(id: string) {
        $('#' + id).click();
    }

    uploadSignatureTrigger(fileInput: any) {
        if (fileInput.target.files && fileInput.target.files[0]) {
            var reader = new FileReader();

            this.signatureModel.name = fileInput.target.files[0].name;
            this.signatureModel.size = fileInput.target.files[0].size;
            this.signatureModel.type = fileInput.target.files[0].type;
            if (this.signatureModel.label == '') {
                this.signatureModel.label = this.signatureModel.name;
            }

            reader.readAsDataURL(fileInput.target.files[0]);

            reader.onload = (value: any) => {
                window['angularProfileComponent'].componentAfterUpload(value.target.result);
                this.submitSignature();
            };

        }
    }

    dndUploadSignature(event: any) {
        if (event.mouseEvent.dataTransfer.files && event.mouseEvent.dataTransfer.files[0]) {
            var reader = new FileReader();

            this.signatureModel.name = event.mouseEvent.dataTransfer.files[0].name;
            this.signatureModel.size = event.mouseEvent.dataTransfer.files[0].size;
            this.signatureModel.type = event.mouseEvent.dataTransfer.files[0].type;
            if (this.signatureModel.label == '') {
                this.signatureModel.label = this.signatureModel.name;
            }

            reader.readAsDataURL(event.mouseEvent.dataTransfer.files[0]);

            reader.onload = (value: any) => {
                window['angularProfileComponent'].componentAfterUpload(value.target.result);
                this.submitSignature();
            };
        }
    }

    displaySignatureEditionForm(index: number) {
        this.selectedSignature = index;
        this.selectedSignatureLabel = this.user.signatures[index].signature_label;
    }

    changeEmailSignature(i: any) {
        this.mailSignatureModel.selected = i;

        tinymce.get('emailSignature').setContent(this.user.emailSignatures[i].html_body);
        this.mailSignatureModel.title = this.user.emailSignatures[i].title;
    }

    resetEmailSignature() {
        this.mailSignatureModel.selected = -1;

        tinymce.get('emailSignature').setContent('');
        this.mailSignatureModel.title = '';

    }

    addBasketRedirection(newUser: any) {
        let basketsRedirect: any[] = [];

        this.selectionBaskets.selected.forEach((elem: any) => {
            basketsRedirect.push(
                {
                    actual_user_id: newUser.serialId,
                    basket_id: elem.basket_id,
                    group_id: elem.groupSerialId,
                    originalOwner: null
                }
            )
        });

        let r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.redirectBasket'));

        if (r) {
            this.http.post('../rest/users/' + this.user.id + '/redirectedBaskets', basketsRedirect)
                .subscribe((data: any) => {
                    this.user.baskets = data['baskets'];
                    this.user.redirectedBaskets = data['redirectedBaskets'];
                    this.selectionBaskets.clear();
                    this.notify.success(this.translate.instant('lang.basketUpdated'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    delBasketRedirection(basket: any, i: number) {
        let r = confirm(this.translate.instant('lang.confirmAction'));

        if (r) {
            this.http.delete('../rest/users/' + this.user.id + '/redirectedBaskets?redirectedBasketIds[]=' + basket.id)
                .subscribe((data: any) => {
                    this.user.baskets = data['baskets'];
                    this.user.redirectedBaskets.splice(i, 1);
                    this.notify.success(this.translate.instant('lang.basketUpdated'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    delBasketAssignRedirection(basket: any, i: number) {
        let r = confirm(this.translate.instant('lang.confirmAction'));

        if (r) {
            this.http.delete('../rest/users/' + this.user.id + '/redirectedBaskets?redirectedBasketIds[]=' + basket.id)
                .subscribe((data: any) => {
                    this.user.baskets = data['baskets'];
                    this.user.assignedBaskets.splice(i, 1);
                    this.notify.success(this.translate.instant('lang.basketUpdated'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    reassignBasketRedirection(newUser: any, basket: any, i: number) {
        let r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.redirectBasket'));

        if (r) {
            this.http.post('../rest/users/' + this.user.id + '/redirectedBaskets', [
                {
                    'actual_user_id': newUser.serialId,
                    'basket_id': basket.basket_id,
                    'group_id': basket.group_id,
                    'originalOwner': basket.owner_user_id,
                }
            ])
                .subscribe((data: any) => {
                    this.user.baskets = data['baskets'];
                    this.user.assignedBaskets.splice(i, 1);
                    this.notify.success(this.translate.instant('lang.basketUpdated'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    updateBasketColor(i: number, y: number) {
        this.http.put('../rest/currentUser/groups/' + this.user.regroupedBaskets[i].groupSerialId + '/baskets/' + this.user.regroupedBaskets[i].baskets[y].basket_id, { 'color': this.user.regroupedBaskets[i].baskets[y].color })
            .subscribe((data: any) => {
                this.user.regroupedBaskets = data.userBaskets;
                this.notify.success(this.translate.instant('lang.modificationSaved'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    activateAbsence() {
        let r = confirm(this.translate.instant('lang.confirmToBeAbsent'));

        if (r) {
            this.http.put('../rest/users/' + this.user.id + '/status', { 'status': 'ABS' })
                .subscribe(() => {
                    this.authService.logout();
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    askRedirectBasket() {
        this.myBasketExpansionPanel = false;
        this.viewPanels.forEach(p => p.close());
        let r = confirm(this.translate.instant('lang.askRedirectBasketBeforeAbsence'));
        if (r) {
            this.selectedIndex = 1;
            setTimeout(() => {
                this.myBasketExpansionPanel = true;
            }, 0);
        } else {
            this.activateAbsence();
        }
    }

    updatePassword() {
        this.passwordModel.currentPassword = this.firstFormGroup.controls['currentPasswordCtrl'].value;
        this.passwordModel.newPassword = this.firstFormGroup.controls['newPasswordCtrl'].value;
        this.passwordModel.reNewPassword = this.firstFormGroup.controls['retypePasswordCtrl'].value;
        this.http.put('../rest/users/' + this.user.id + '/password', this.passwordModel)
            .subscribe((data: any) => {
                this.showPassword = false;
                this.passwordModel = {
                    currentPassword: '',
                    newPassword: '',
                    reNewPassword: '',
                };
                this.notify.success(this.translate.instant('lang.passwordUpdated'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    submitEmailSignature() {
        this.mailSignatureModel.htmlBody = tinymce.get('emailSignature').getContent();

        this.http.post('../rest/currentUser/emailSignature', this.mailSignatureModel)
            .subscribe((data: any) => {
                if (data.errors) {
                    this.notify.error(data.errors);
                } else {
                    this.user.emailSignatures = data.emailSignatures;
                    this.mailSignatureModel = {
                        selected: -1,
                        htmlBody: '',
                        title: '',
                    };
                    tinymce.get('emailSignature').setContent('');
                    this.notify.success(this.translate.instant('lang.emailSignatureAdded'));
                }
            });
    }

    updateEmailSignature() {
        this.mailSignatureModel.htmlBody = tinymce.get('emailSignature').getContent();
        var id = this.user.emailSignatures[this.mailSignatureModel.selected].id;

        this.http.put('../rest/currentUser/emailSignature/' + id, this.mailSignatureModel)
            .subscribe((data: any) => {
                if (data.errors) {
                    this.notify.error(data.errors);
                } else {
                    this.user.emailSignatures[this.mailSignatureModel.selected].title = data.emailSignature.title;
                    this.user.emailSignatures[this.mailSignatureModel.selected].html_body = data.emailSignature.html_body;
                    this.notify.success(this.translate.instant('lang.emailSignatureUpdated'));
                }
            });
    }

    deleteEmailSignature() {
        let r = confirm(this.translate.instant('lang.confirmDeleteMailSignature'));

        if (r) {
            var id = this.user.emailSignatures[this.mailSignatureModel.selected].id;

            this.http.delete('../rest/currentUser/emailSignature/' + id)
                .subscribe((data: any) => {
                    if (data.errors) {
                        this.notify.error(data.errors);
                    } else {
                        this.user.emailSignatures = data.emailSignatures;
                        this.mailSignatureModel = {
                            selected: -1,
                            htmlBody: '',
                            title: '',
                        };
                        tinymce.get('emailSignature').setContent('');
                        this.notify.success(this.translate.instant('lang.emailSignatureDeleted'));
                    }
                });
        }
    }

    submitSignature() {
        this.http.post('../rest/users/' + this.user.id + '/signatures', this.signatureModel)
            .subscribe((data: any) => {
                this.user.signatures = data.signatures;
                this.signatureModel = {
                    base64: '',
                    base64ForJs: '',
                    name: '',
                    type: '',
                    size: 0,
                    label: '',
                };
                this.notify.success(this.translate.instant('lang.signatureAdded'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    updateSignature(signature: any) {
        this.http.put('../rest/users/' + this.user.id + '/signatures/' + signature.id, { 'label': signature.signature_label })
            .subscribe((data: any) => {
                this.notify.success(this.translate.instant('lang.signatureUpdated'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    deleteSignature(id: number) {
        let r = confirm(this.translate.instant('lang.confirmDeleteSignature'));

        if (r) {
            this.http.delete('../rest/users/' + this.user.id + '/signatures/' + id)
                .subscribe((data: any) => {
                    this.user.signatures = data.signatures;
                    this.notify.success(this.translate.instant('lang.signatureDeleted'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    onSubmit() {
        this.http.put('../rest/currentUser/profile', this.user)
            .subscribe(() => {
                this.notify.success(this.translate.instant('lang.modificationSaved'));
                this.headerService.user.firstname = this.user.firstname;
                this.headerService.user.lastname = this.user.lastname;
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    updateUserPreferences() {
        this.http.put('../rest/currentUser/profile/preferences', { documentEdition: this.user.preferences.documentEdition })
            .subscribe(() => {
                this.notify.success(this.translate.instant('lang.modificationSaved'));
                this.headerService.resfreshCurrentUser();
            }, (err) => {
                this.notify.error(err.error.errors);
            });
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

    changePasswd() {
        this.http.get('../rest/passwordRules')
            .subscribe((data: any) => {
                let valArr: ValidatorFn[] = [];
                let ruleTextArr: String[] = [];
                let otherRuleTextArr: String[] = [];

                valArr.push(Validators.required);

                data.rules.forEach((rule: any) => {
                    if (rule.label == 'minLength') {
                        this.passwordRules.minLength.enabled = rule.enabled;
                        this.passwordRules.minLength.value = rule.value;
                        if (rule.enabled) {
                            valArr.push(Validators.minLength(this.passwordRules.minLength.value));
                            ruleTextArr.push(rule.value + ' ' + this.translate.instant('lang.password' + rule.label));
                        }


                    } else if (rule.label == 'complexityUpper') {
                        this.passwordRules.complexityUpper.enabled = rule.enabled;
                        this.passwordRules.complexityUpper.value = rule.value;
                        if (rule.enabled) {
                            valArr.push(this.regexValidator(new RegExp('[A-Z]'), { 'complexityUpper': '' }));
                            ruleTextArr.push(this.translate.instant('lang.password' + rule.label));
                        }


                    } else if (rule.label == 'complexityNumber') {
                        this.passwordRules.complexityNumber.enabled = rule.enabled;
                        this.passwordRules.complexityNumber.value = rule.value;
                        if (rule.enabled) {
                            valArr.push(this.regexValidator(new RegExp('[0-9]'), { 'complexityNumber': '' }));
                            ruleTextArr.push(this.translate.instant('lang.password' + rule.label));
                        }


                    } else if (rule.label == 'complexitySpecial') {
                        this.passwordRules.complexitySpecial.enabled = rule.enabled;
                        this.passwordRules.complexitySpecial.value = rule.value;
                        if (rule.enabled) {
                            valArr.push(this.regexValidator(new RegExp('[^A-Za-z0-9]'), { 'complexitySpecial': '' }));
                            ruleTextArr.push(this.translate.instant('lang.password' + rule.label));
                        }
                    } else if (rule.label == 'renewal') {
                        this.passwordRules.renewal.enabled = rule.enabled;
                        this.passwordRules.renewal.value = rule.value;
                        if (rule.enabled) {
                            otherRuleTextArr.push(this.translate.instant('lang.password' + rule.label) + ' <b>' + rule.value + ' ' + this.translate.instant('lang.days') + '</b>. ' + this.translate.instant('lang.password2' + rule.label) + '.');
                        }
                    } else if (rule.label == 'historyLastUse') {
                        this.passwordRules.historyLastUse.enabled = rule.enabled;
                        this.passwordRules.historyLastUse.value = rule.value;
                        if (rule.enabled) {
                            otherRuleTextArr.push(this.translate.instant('lang.passwordhistoryLastUseDesc') + ' <b>' + rule.value + '</b> ' + this.translate.instant('lang.passwordhistoryLastUseDesc2') + '.');
                        }
                    }

                });
                this.ruleText = ruleTextArr.join(', ');
                this.otherRuleText = otherRuleTextArr.join('<br/>');
                this.firstFormGroup.controls['newPasswordCtrl'].setValidators(valArr);
            }, (err) => {
                this.notify.error(err.error.errors);
            });

        this.firstFormGroup = this._formBuilder.group({
            newPasswordCtrl: [
                ''
            ],
            retypePasswordCtrl: [
                '',
                Validators.compose([Validators.required])
            ],
            currentPasswordCtrl: [
                '',
                Validators.compose([Validators.required])
            ]

        }, {
            validator: this.matchValidator
        });
        this.validPassword = false;
        this.firstFormGroup.controls['currentPasswordCtrl'].setErrors(null);
        this.firstFormGroup.controls['newPasswordCtrl'].setErrors(null);
        this.firstFormGroup.controls['retypePasswordCtrl'].setErrors(null);
        this.selectedIndex = 0;
        this.showPassword = true;
    }

    matchValidator(group: FormGroup) {

        if (group.controls['newPasswordCtrl'].value == group.controls['retypePasswordCtrl'].value) {
            return false;
        } else {
            group.controls['retypePasswordCtrl'].setErrors({ 'mismatch': true });
            return { 'mismatch': true };
        }
    }

    getErrorMessage() {
        if (this.firstFormGroup.controls['newPasswordCtrl'].value != this.firstFormGroup.controls['retypePasswordCtrl'].value) {
            this.firstFormGroup.controls['retypePasswordCtrl'].setErrors({ 'mismatch': true });
        } else {
            this.firstFormGroup.controls['retypePasswordCtrl'].setErrors(null);
        }
        if (this.firstFormGroup.controls['newPasswordCtrl'].hasError('required')) {
            return this.translate.instant('lang.requiredField') + ' !';

        } else if (this.firstFormGroup.controls['newPasswordCtrl'].hasError('minlength') && this.passwordRules.minLength.enabled) {
            return this.passwordRules.minLength.value + ' ' + this.translate.instant('lang.passwordminLength') + ' !';

        } else if (this.firstFormGroup.controls['newPasswordCtrl'].errors != null && this.firstFormGroup.controls['newPasswordCtrl'].errors.complexityUpper !== undefined && this.passwordRules.complexityUpper.enabled) {
            return this.translate.instant('lang.passwordcomplexityUpper') + ' !';

        } else if (this.firstFormGroup.controls['newPasswordCtrl'].errors != null && this.firstFormGroup.controls['newPasswordCtrl'].errors.complexityNumber !== undefined && this.passwordRules.complexityNumber.enabled) {
            return this.translate.instant('lang.passwordcomplexityNumber') + ' !';

        } else if (this.firstFormGroup.controls['newPasswordCtrl'].errors != null && this.firstFormGroup.controls['newPasswordCtrl'].errors.complexitySpecial !== undefined && this.passwordRules.complexitySpecial.enabled) {
            return this.translate.instant('lang.passwordcomplexitySpecial') + ' !';

        } else {
            this.firstFormGroup.controls['newPasswordCtrl'].setErrors(null);
            this.validPassword = true;
            return '';
        }
    }

    showActions(basket: any) {
        $('#' + basket.basket_id + '_' + basket.group_id).show();
    }

    hideActions(basket: any) {
        $('#' + basket.basket_id + '_' + basket.group_id).hide();
    }

    toggleAddGrp() {
        this.initGroupsContact();
        $('#contactsGroupFormUp').toggle();
        $('#contactsGroupList').toggle();
    }
    toggleAddContactGrp() {
        $('#contactsGroupFormAdd').toggle();
        // $('#contactsGroup').toggle();
    }

    changeTabContactGrp(event: any) {
        this.selectedIndexContactsGrp = event;
        if (event == 0) {
            this.initGroupsContact();
        }
    }

    syncMP() {
        this.loadingSign = true;

        this.http.put('../rest/users/' + this.user.id + '/externalSignatures', {})
            .subscribe((data: any) => {
                this.loadingSign = false;
                this.notify.success(this.translate.instant('lang.signsSynchronized'));
            }, (err) => {
                this.loadingSign = false;
                this.notify.handleErrors(err);
            });
    }
}
