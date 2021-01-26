import { Component, OnInit, NgZone, ViewChild, Inject, TemplateRef, ViewContainerRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { MatPaginator } from '@angular/material/paginator';
import { MatSidenav } from '@angular/material/sidenav';
import { MatSort } from '@angular/material/sort';
import { MatTableDataSource } from '@angular/material/table';
import { FormGroup, Validators, AbstractControl, ValidationErrors, ValidatorFn, FormBuilder } from '@angular/forms';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { SelectionModel } from '@angular/cdk/collections';
import { AccountLinkComponent } from './account-link/account-link.component';
import { AppService } from '@service/app.service';
import { PrivilegeService } from '@service/privileges.service';
import { MaarchFlatTreeComponent } from '../../../plugins/tree/maarch-flat-tree.component';
import { environment } from '../../../environments/environment';

declare var $: any;

@Component({
    templateUrl: 'user-administration.component.html',
    styleUrls: ['user-administration.component.scss']
})
export class UserAdministrationComponent implements OnInit {

    @ViewChild('snav2', { static: true }) public sidenavRight: MatSidenav;
    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;
    @ViewChild('maarchTree', { static: false }) maarchTree: MaarchFlatTreeComponent;


    
    loading: boolean = false;
    dialogRef: MatDialogRef<any>;
    highlightMe: boolean = false;
    config: any = {};
    serialId: number;
    userId: string;
    mode: string = '';
    user: any = {
        mode: 'standard',
        authorizedApi: ''
    };
    _search: string = '';
    creationMode: boolean;

    signatureModel: any = {
        base64: '',
        base64ForJs: '',
        name: '',
        type: '',
        size: 0,
        label: '',
    };
    userAbsenceModel: any[] = [];
    userList: any[] = [];
    maarchParapheurLink: any = {
        login: '',
        picture: ''
    };
    selectedSignature: number = -1;
    selectedSignatureLabel: string = '';
    loadingSign: boolean = false;
    data: any[] = [];
    CurrentYear: number = new Date().getFullYear();
    currentMonth: number = new Date().getMonth() + 1;
    minDate: Date = new Date();
    firstFormGroup: FormGroup;
    ruleText: string = '';
    otherRuleText: string;
    validPassword: boolean = false;
    showPassword: boolean = false;
    hidePassword: boolean = true;
    passwordModel: any = {
        currentPassword: '',
        newPassword: '',
        reNewPassword: ''
    };
    passwordRules: any = {
        minLength: { enabled: false, value: 0 },
        complexityUpper: { enabled: false, value: 0 },
        complexityNumber: { enabled: false, value: 0 },
        complexitySpecial: { enabled: false, value: 0 },
        renewal: { enabled: false, value: 0 },
        historyLastUse: { enabled: false, value: 0 }
    };

    displayedColumns = ['event_date', 'record_id', 'info', 'remote_ip'];
    dataSource = new MatTableDataSource(this.data);
    selectedTabIndex: number = 0;
    maarchParapheurConnectionStatus = true;

    canViewPersonalDatas: boolean = false;
    canManagePersonalDatas: boolean = false;

    adminModes: any[] = [
        {
            id: 'standard',
            label: this.translate.instant('lang.standard')
        },
        {
            id: 'root_visible',
            label: this.translate.instant('lang.root_visible')
        },
        {
            id: 'root_invisible',
            label: this.translate.instant('lang.root_invisible')
        },
        {
            id: 'rest',
            label: this.translate.instant('lang.rest')
        }
    ];

    appVersion: string = environment.VERSION.split('.')[0] + '.' + environment.VERSION.split('.')[1];

    @ViewChild(MatPaginator, { static: false }) paginator: MatPaginator;
    @ViewChild(MatSort, { static: false }) sort: MatSort;

    // Redirect Baskets
    selectionBaskets = new SelectionModel<Element>(true, []);

    applyFilter(filterValue: string) {
        filterValue = filterValue.trim();
        filterValue = filterValue.toLowerCase();
        this.dataSource.filter = filterValue;
    }

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


    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private route: ActivatedRoute,
        private router: Router,
        private zone: NgZone,
        private notify: NotificationService,
        public dialog: MatDialog,
        public headerService: HeaderService,
        private _formBuilder: FormBuilder,
        public appService: AppService,
        private privilegeService: PrivilegeService,
        private viewContainerRef: ViewContainerRef
    ) {
        window['angularUserAdministrationComponent'] = {
            componentAfterUpload: (base64Content: any) => this.processAfterUpload(base64Content),
        };
    }

    ngOnInit(): void {
        this.loading = true;

        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');

        this.route.params.subscribe((params: any) => {

            if (typeof params['id'] === 'undefined') {

                this.headerService.setHeader(this.translate.instant('lang.userCreation'));
                this.creationMode = true;
                this.canViewPersonalDatas = false;
                this.canManagePersonalDatas = this.privilegeService.hasCurrentUserPrivilege('manage_personal_data');
                this.loading = false;
            } else {

                this.creationMode = false;
                this.serialId = params['id'];
                this.http.get('../rest/users/' + this.serialId + '/details')
                    .subscribe((data: any) => {
                        this.user = data;

                        if (this.user.mode === 'rest') {
                            this.user.authorizedApi = this.user.authorizedApi.join('\n');
                        }

                        if (this.headerService.user.id === this.user.id) {
                            this.canViewPersonalDatas = true;
                            this.canManagePersonalDatas = true;
                        } else {
                            this.canViewPersonalDatas = this.privilegeService.hasCurrentUserPrivilege('view_personal_data');
                            this.canManagePersonalDatas = this.privilegeService.hasCurrentUserPrivilege('manage_personal_data');
                        }

                        if (this.canManagePersonalDatas) {
                            this.canViewPersonalDatas = true;
                        }
                        if (!this.canViewPersonalDatas) {
                            this.user.phone = '****';
                        }
                        this.data = data.history;
                        this.userId = data.user_id;
                        this.minDate = new Date(this.CurrentYear + '-' + this.currentMonth + '-01');
                        this.headerService.setHeader(this.translate.instant('lang.userModification'), data.firstname + ' ' + data.lastname);

                        if (this.user.external_id.maarchParapheur !== undefined) {
                            this.checkInfoMaarchParapheurAccount();
                        }

                        this.loading = false;
                        setTimeout(() => {
                            this.dataSource = new MatTableDataSource(this.data);
                            this.dataSource.paginator = this.paginator;
                            this.dataSource.sort = this.sort;
                        }, 0);
                    }, (err) => {
                        this.notify.handleErrors(err);
                    });
            }
        });
    }

    checkInfoMaarchParapheurAccount() {
        this.http.get('../rest/users/' + this.serialId + '/statusInMaarchParapheur')
            .subscribe((data: any) => {
                this.maarchParapheurLink.login = data.link;
                this.loading = false;
                if (this.maarchParapheurLink.login !== '') {
                    this.loadAvatarMaarchParapheur(this.user.external_id.maarchParapheur);
                } else {
                    this.maarchParapheurConnectionStatus = false;
                }
            });
    }

    linkMaarchParapheurAccount() {
        const dialogRef = this.dialog.open(AccountLinkComponent, { panelClass: 'maarch-modal', autoFocus: false, data: { user: this.user } });
        dialogRef.afterClosed().subscribe(result => {
            if (result) {
                if (result.inMaarchParapheur) {
                    this.linkAccountToMaarchParahpeur(result.id);
                } else {
                    this.createAccountToMaarchParahpeur(result.id, result.login);
                }

            }
        });
    }

    linkAccountToMaarchParahpeur(externalId: number) {
        this.http.put('../rest/users/' + this.serialId + '/linkToMaarchParapheur', { maarchParapheurUserId: externalId })
            .subscribe(() => {
                this.user.canCreateMaarchParapheurUser = false;
                this.user.external_id['maarchParapheur'] = externalId;
                this.checkInfoMaarchParapheurAccount();
                this.notify.success(this.translate.instant('lang.accountLinked'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    createAccountToMaarchParahpeur(id: number, login: string) {
        this.http.put('../rest/users/' + id + '/createInMaarchParapheur', { login: login })
            .subscribe((data: any) => {
                this.user.canCreateMaarchParapheurUser = false;
                this.user.external_id['maarchParapheur'] = data.externalId;
                this.checkInfoMaarchParapheurAccount();
                this.notify.success(this.translate.instant('lang.accountAdded'));
            }, (err) => {
                if (err.error.errors === 'Login already exists') {
                    err.error.errors = this.translate.instant('lang.loginAlreadyExistsInMaarchParapheur');
                }
                this.notify.error(err.error.errors);
            });
    }

    loadAvatarMaarchParapheur(externalId: number) {
        this.http.get('../rest/maarchParapheur/user/' + externalId + '/picture')
            .subscribe((data: any) => {
                this.maarchParapheurLink.picture = data.picture;

            }, (err) => {
                this.notify.handleErrors(err);
            });
    }

    unlinkMaarchParapheurAccount() {
        const r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.unlinkAccount'));

        if (r) {
            this.http.put('../rest/users/' + this.serialId + '/unlinkToMaarchParapheur', {})
                .subscribe(() => {
                    this.user.canCreateMaarchParapheurUser = true;
                    this.maarchParapheurLink.login = '';
                    this.maarchParapheurLink.picture = '';
                    this.notify.success(this.translate.instant('lang.accountUnlinked'));
                    this.maarchParapheurConnectionStatus = true;
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    toogleRedirect(basket: any) {
        $('#redirectUser_' + basket.group_id + '_' + basket.basket_id).toggle();

        this.http.get('../rest/users')
            .subscribe((data: any) => {
                // this.userList = data['users'];

            }, (err) => {
                this.notify.handleErrors(err);
            });
    }

    initService() {
        if (this.maarchTree.rawData.length === 0) {
            this.maarchTree.initData(this.user.allEntities.map((ent: any) => {
                return {
                    ...ent,
                    parent_id : ent.parent,
            };
            }));
        }
        /*if ($('.jstree-container-ul').length === 0) {
            $('#jstree').jstree({
                'checkbox': {
                    'three_state': false // no cascade selection
                },
                'core': {
                    force_text: true,
                    'themes': {
                        'name': 'proton',
                        'responsive': true
                    },
                    'data': this.user.allEntities
                },
                'plugins': ['checkbox', 'search']
            });
            $('#jstree')
                // listen for event
                .on('select_node.jstree', (e: any, data: any) => {
                    if (this.mode === '') {
                        this.addEntity(data.node.id);
                    }
                }).on('deselect_node.jstree', (e: any, data: any) => {
                    this.deleteEntity(data.node.id);
                })
                // create the instance
                .jstree();

            let to: any = false;
            $('#jstree_search').keyup(function () {
                if (to) { clearTimeout(to); }
                to = setTimeout(function () {
                    const v: any = $('#jstree_search').val();
                    $('#jstree').jstree(true).search(v);
                }, 250);
            });
        }*/
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

    clickOnUploader(id: string) {
        $('#' + id).click();
    }

    uploadSignatureTrigger(fileInput: any) {
        if (fileInput.target.files && fileInput.target.files[0]) {
            const reader = new FileReader();

            this.signatureModel.name = fileInput.target.files[0].name;
            this.signatureModel.size = fileInput.target.files[0].size;
            this.signatureModel.type = fileInput.target.files[0].type;
            if (this.signatureModel.label === '') {
                this.signatureModel.label = this.signatureModel.name;
            }

            reader.readAsDataURL(fileInput.target.files[0]);

            reader.onload = (value: any) => {
                window['angularUserAdministrationComponent'].componentAfterUpload(value.target.result);
                this.submitSignature();
            };
        }
    }

    displaySignatureEditionForm(index: number) {
        this.selectedSignature = index;
        this.selectedSignatureLabel = this.user.signatures[index].signature_label;
    }

    resendActivationNotification() {
        let r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.sendActivationNotification'));

        if (r) {
            this.http.put('../rest/users/' + this.serialId + '/accountActivationNotification', {})
                .subscribe((data: any) => {
                    this.notify.success(this.translate.instant('lang.activationNotificationSend'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    toggleGroup(group: any) {
        if ($('#' + group.group_id + '-input').is(':checked') === true) {
            const groupReq = {
                'groupId': group.group_id,
                'role': group.role
            };
            this.http.post('../rest/users/' + this.serialId + '/groups', groupReq)
                .subscribe(async (data: any) => {
                    this.user.groups = data.groups;
                    this.user.baskets = data.baskets;
                    if (this.headerService.user.id == this.serialId) {
                        await this.headerService.resfreshCurrentUser();
                        this.privilegeService.resfreshUserShortcuts();
                    }
                    this.notify.success(this.translate.instant('lang.groupAdded'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        } else {
            this.http.delete('../rest/users/' + this.serialId + '/groups/' + group.group_id)
                .subscribe(async (data: any) => {
                    this.user.groups = data.groups;
                    this.user.baskets = data.baskets;
                    this.user.redirectedBaskets = data.redirectedBaskets;
                    if (this.headerService.user.id == this.serialId) {
                        await this.headerService.resfreshCurrentUser();
                        this.privilegeService.resfreshUserShortcuts();
                    }
                    this.notify.success(this.translate.instant('lang.groupDeleted'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    updateGroup(group: any) {
        this.http.put('../rest/users/' + this.serialId + '/groups/' + group.group_id, group)
            .subscribe((data: any) => {
                this.notify.success(this.translate.instant('lang.groupUpdated'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    addEntity(entities: any[]) {
        entities.forEach(ent => {
            const entity = {
                'entityId': ent.entity_id,
                'role': ''
            };
            this.http.post('../rest/users/' + this.serialId + '/entities', entity)
                .subscribe((data: any) => {
                    this.user.entities = data.entities;
                    this.user.allEntities = data.allEntities;
                    if (this.headerService.user.id == this.serialId) {
                        this.headerService.resfreshCurrentUser();
                    }
                    this.notify.success(this.translate.instant('lang.entityAdded'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        });

    }

    updateEntity(entity: any) {
        this.http.put('../rest/users/' + this.serialId + '/entities/' + entity.entity_id, entity)
            .subscribe(() => {
                this.notify.success(this.translate.instant('lang.entityUpdated'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    updatePrimaryEntity(entity: any) {
        this.http.put('../rest/users/' + this.serialId + '/entities/' + entity.entity_id + '/primaryEntity', {})
            .subscribe((data: any) => {
                this.user['entities'] = data.entities;
                this.notify.success(this.translate.instant('lang.entityTooglePrimary') + ' « ' + entity.entity_id + ' »');
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    deleteEntity(entities: any[]) {

        entities.forEach(ent => {
            const entityId = ent.entity_id;
            // first check confidential state
            this.http.get('../rest/users/' + this.serialId + '/entities/' + entityId)
                .subscribe((data: any) => {
                    if (!data['hasConfidentialityInstances'] && !data['hasListTemplates']) {
                        this.http.delete('../rest/users/' + this.serialId + '/entities/' + entityId)
                            .subscribe((dataEntities: any) => {
                                this.user.entities = dataEntities.entities;
                                this.user.allEntities = dataEntities.allEntities;
                                if (this.headerService.user.id == this.serialId) {
                                    this.headerService.resfreshCurrentUser();
                                }
                                this.notify.success(this.translate.instant('lang.entityDeleted'));
                            }, (err) => {
                                this.notify.error(err.error.errors);
                            });
                    } else {
                        this.config = { panelClass: 'maarch-modal', data: { hasConfidentialityInstances: data['hasConfidentialityInstances'], hasListTemplates: data['hasListTemplates'] } };
                        this.dialogRef = this.dialog.open(UserAdministrationRedirectModalComponent, this.config);
                        this.dialogRef.afterClosed().subscribe((result: any) => {
                            this.mode = 'delete';
                            if (result) {
                                this.mode = result.processMode;
                                this.http.request('DELETE', '../rest/users/' + this.serialId + '/entities/' + entityId, { body: { 'mode': this.mode, 'newUser': result.newUser } })
                                    .subscribe((dataEntities: any) => {
                                        this.user.entities = dataEntities.entities;
                                        this.user.allEntities = dataEntities.allEntities;
                                        if (this.headerService.user.id == this.serialId) {
                                            this.headerService.resfreshCurrentUser();
                                        }
                                        this.notify.success(this.translate.instant('lang.entityDeleted'));
                                    }, (err) => {
                                        this.notify.error(err.error.errors);
                                    });
                            } else {
                                this.maarchTree.toggleNode(
                                    this.maarchTree.dataSource.data,
                                    {
                                        selected: true,
                                        opened: true
                                    },
                                    [ent.id]
                                );
                                // $('#jstree').jstree('select_node', entityId);
                                this.mode = '';
                            }
                            this.dialogRef = null;
                        });
                    }

                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        });

    }

    submitSignature() {
        this.http.post('../rest/users/' + this.serialId + '/signatures', this.signatureModel)
            .subscribe((data: any) => {
                this.user.signatures = data.signatures;
                this.notify.success(this.translate.instant('lang.signAdded'));
                this.signatureModel = {
                    base64: '',
                    base64ForJs: '',
                    name: '',
                    type: '',
                    size: 0,
                    label: '',
                };
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    updateSignature(selectedSignature: any) {
        const id = this.user.signatures[selectedSignature].id;
        const label = this.user.signatures[selectedSignature].signature_label;

        this.http.put('../rest/users/' + this.serialId + '/signatures/' + id, { 'label': label })
            .subscribe((data: any) => {
                this.user.signatures[selectedSignature].signature_label = data.signature.signature_label;
                this.notify.success(this.translate.instant('lang.signUpdated'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    deleteSignature(signature: any) {
        const r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.delete') + ' « ' + signature.signature_label + ' »');

        if (r) {
            this.http.delete('../rest/users/' + this.serialId + '/signatures/' + signature.id)
                .subscribe((data: any) => {
                    this.user.signatures = data.signatures;
                    this.notify.success(this.translate.instant('lang.signDeleted'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    test(event: any) {
        if (event.mouseEvent.dataTransfer.files && event.mouseEvent.dataTransfer.files[0]) {
            const reader = new FileReader();

            this.signatureModel.name = event.mouseEvent.dataTransfer.files[0].name;
            this.signatureModel.size = event.mouseEvent.dataTransfer.files[0].size;
            this.signatureModel.type = event.mouseEvent.dataTransfer.files[0].type;
            if (this.signatureModel.label === '') {
                this.signatureModel.label = this.signatureModel.name;
            }

            reader.readAsDataURL(event.mouseEvent.dataTransfer.files[0]);

            reader.onload = (value: any) => {
                window['angularUserAdministrationComponent'].componentAfterUpload(value.target.result);
                this.submitSignature();
            };
        }
    }

    addBasketRedirection(newUser: any) {
        const basketsRedirect: any[] = [];

        this.selectionBaskets.selected.forEach((elem: any) => {
            basketsRedirect.push(
                {
                    actual_user_id: newUser.serialId,
                    basket_id: elem.basket_id,
                    group_id: elem.groupSerialId,
                    originalOwner: null
                }
            );
        });

        const r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.redirectBasket'));

        if (r) {
            this.http.post('../rest/users/' + this.serialId + '/redirectedBaskets', basketsRedirect)
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

    reassignBasketRedirection(newUser: any, basket: any, i: number) {
        const r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.redirectBasket'));

        if (r) {
            this.http.post('../rest/users/' + this.serialId + '/redirectedBaskets', [
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

    delBasketRedirection(basket: any, i: number) {
        const r = confirm(this.translate.instant('lang.confirmAction'));

        if (r) {
            this.http.delete('../rest/users/' + this.serialId + '/redirectedBaskets?redirectedBasketIds[]=' + basket.id)
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
        const r = confirm(this.translate.instant('lang.confirmAction'));

        if (r) {
            this.http.delete('../rest/users/' + this.serialId + '/redirectedBaskets?redirectedBasketIds[]=' + basket.id)
                .subscribe((data: any) => {
                    this.user.baskets = data['baskets'];
                    this.user.assignedBaskets.splice(i, 1);
                    this.notify.success(this.translate.instant('lang.basketUpdated'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    toggleBasket(state: boolean) {
        const basketsDisable: any = [];
        this.user.baskets.forEach((elem: any) => {
            this.selectionBaskets.selected.forEach((selected: any) => {
                if (elem.basket_id === selected.basket_id && elem.group_id === selected.group_id && elem.allowed !== state) {
                    elem.allowed = state;
                    basketsDisable.push({ 'basketId': elem.basket_id, 'groupSerialId': elem.groupSerialId, 'allowed': state });
                }
            });
        });
        if (basketsDisable.length > 0) {
            this.http.put('../rest/users/' + this.serialId + '/baskets', { 'baskets': basketsDisable })
                .subscribe((data: any) => {
                    this.selectionBaskets.clear();
                    this.notify.success(this.translate.instant('lang.basketsUpdated'));
                }, (err: any) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    activateAbsence() {
        this.http.put('../rest/users/' + this.serialId + '/status', { 'status': 'ABS' })
            .subscribe((data: any) => {
                this.user.status = data.user.status;
                this.userAbsenceModel = [];
                this.notify.success(this.translate.instant('lang.absOn'));
            }, (err) => {
                this.notify.error(err.error.errors);
            });
    }

    desactivateAbsence() {
        this.http.put('../rest/users/' + this.serialId + '/status', { 'status': 'OK' })
            .subscribe((data: any) => {
                this.user.status = data.user.status;
                this.notify.success(this.translate.instant('lang.absOff'));
            }, (err: any) => {
                this.notify.error(err.error.errors);
            });
    }

    getErrorMessage() {
        if (this.firstFormGroup.controls['newPasswordCtrl'].value !== this.firstFormGroup.controls['retypePasswordCtrl'].value) {
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

    matchValidator(group: FormGroup) {
        if (group.controls['newPasswordCtrl'].value === group.controls['retypePasswordCtrl'].value) {
            return false;
        } else {
            group.controls['retypePasswordCtrl'].setErrors({ 'mismatch': true });
            return { 'mismatch': true };
        }
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
                const valArr: ValidatorFn[] = [];
                const ruleTextArr: String[] = [];
                const otherRuleTextArr: String[] = [];

                valArr.push(Validators.required);

                data.rules.forEach((rule: any) => {
                    if (rule.label === 'minLength') {
                        this.passwordRules.minLength.enabled = rule.enabled;
                        this.passwordRules.minLength.value = rule.value;
                        if (rule.enabled) {
                            valArr.push(Validators.minLength(this.passwordRules.minLength.value));
                            ruleTextArr.push(rule.value + ' ' + this.translate.instant('lang.password' + rule.label));
                        }


                    } else if (rule.label === 'complexityUpper') {
                        this.passwordRules.complexityUpper.enabled = rule.enabled;
                        this.passwordRules.complexityUpper.value = rule.value;
                        if (rule.enabled) {
                            valArr.push(this.regexValidator(new RegExp('[A-Z]'), { 'complexityUpper': '' }));
                            ruleTextArr.push(this.translate.instant('lang.password' + rule.label));
                        }


                    } else if (rule.label === 'complexityNumber') {
                        this.passwordRules.complexityNumber.enabled = rule.enabled;
                        this.passwordRules.complexityNumber.value = rule.value;
                        if (rule.enabled) {
                            valArr.push(this.regexValidator(new RegExp('[0-9]'), { 'complexityNumber': '' }));
                            ruleTextArr.push(this.translate.instant('lang.password' + rule.label));
                        }


                    } else if (rule.label === 'complexitySpecial') {
                        this.passwordRules.complexitySpecial.enabled = rule.enabled;
                        this.passwordRules.complexitySpecial.value = rule.value;
                        if (rule.enabled) {
                            valArr.push(this.regexValidator(new RegExp('[^A-Za-z0-9]'), { 'complexitySpecial': '' }));
                            ruleTextArr.push(this.translate.instant('lang.password' + rule.label));
                        }
                    } else if (rule.label === 'renewal') {
                        this.passwordRules.renewal.enabled = rule.enabled;
                        this.passwordRules.renewal.value = rule.value;
                        if (rule.enabled) {
                            otherRuleTextArr.push(this.translate.instant('lang.password' + rule.label) + ' <b>' + rule.value + ' ' + this.translate.instant('lang.days') + '</b>. ' + this.translate.instant('lang.password2' + rule.label) + '.');
                        }
                    } else if (rule.label === 'historyLastUse') {
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
            }, (err: any) => {
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
        this.showPassword = true;
        this.selectedTabIndex = 0;
    }

    updatePassword() {
        this.passwordModel.currentPassword = this.firstFormGroup.controls['currentPasswordCtrl'].value;
        this.passwordModel.newPassword = this.firstFormGroup.controls['newPasswordCtrl'].value;
        this.passwordModel.reNewPassword = this.firstFormGroup.controls['retypePasswordCtrl'].value;
        this.http.put('../rest/users/' + this.serialId + '/password', this.passwordModel)
            .subscribe(() => {
                this.showPassword = false;
                this.passwordModel = {
                    currentPassword: '',
                    newPassword: '',
                    reNewPassword: '',
                };
                this.notify.success(this.translate.instant('lang.passwordUpdated'));
            }, (err: any) => {
                this.notify.error(err.error.errors);
            });
    }

    onSubmit() {
        if (this.creationMode) {
            let r = true;

            this.http.get('../rest/users/' + this.user.userId + '/status')
                .subscribe((data: any) => {
                    let deletedUser = false;
                    if (data.status && data.status === 'DEL') {
                        r = confirm(this.translate.instant('lang.reactivateUserDeleted'));
                        deletedUser = true;
                    }
                    if (r) {
                        this.http.post('../rest/users', this.user)
                            .subscribe((result: any) => {
                                if (deletedUser) {
                                    this.notify.success(this.translate.instant('lang.userUpdated'));
                                } else {
                                    this.notify.success(this.translate.instant('lang.userAdded'));
                                }
                                this.router.navigate(['/administration/users/' + result.id]);
                            }, (err: any) => {
                                this.notify.handleSoftErrors(err);
                            });
                    }
                }, (err: any) => {
                    this.notify.error(err.error.errors);
                });
        } else {
            const user = {
                ...this.user
            };
            if (this.user.mode === 'rest') {
                user.authorizedApi = this.user.authorizedApi.split('\n')[0] !== '' ? this.user.authorizedApi.split('\n') : [];
            }
            this.http.put('../rest/users/' + this.serialId, user)
                .subscribe((data: any) => {
                    if (this.headerService.user.id == this.serialId) {
                        this.headerService.resfreshCurrentUser();
                    }
                    this.notify.success(this.translate.instant('lang.userUpdated'));
                }, (err: any) => {
                    this.notify.handleSoftErrors(err);
                });
        }
    }

    setUserModeLogin(event: any) {
        if (event.checked) {
            this.user.mode = 'rest';
        } else {
            this.user.mode = 'standard';
        }
    }

    sendToMaarchParapheur() {
        const r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.createUserInMaarchParapheur'));

        if (r) {
            this.http.put('../rest/users/' + this.serialId + '/maarchParapheur', '')
                .subscribe((data: any) => {
                    this.notify.success(this.translate.instant('lang.userCreatedInMaarchParapheur'));
                    this.user.external_id['maarchParapheur'] = data.externalId;
                    this.user.canCreateMaarchParapheurUser = false;
                }, (err: any) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    setLowerUserId() {
        this.user.userId = this.user.userId.toLowerCase();
    }

    syncMP() {
        this.loadingSign = true;

        this.http.put('../rest/users/' + this.user.id + '/externalSignatures', {})
            .subscribe((data: any) => {
                this.loadingSign = false;
                this.notify.success(this.translate.instant('lang.signsSynchronized'));
            }, (err) => {
                this.loadingSign = false;
                this.notify.error(err.error.errors);
            });
    }
}

@Component({
    templateUrl: 'user-administration-redirect-modal.component.html',
    styles: ['.mat-dialog-content{max-height: 65vh;width:600px;}']
})
export class UserAdministrationRedirectModalComponent {
    

    redirectUser: String = '';
    processMode: String = '';

    constructor(public http: HttpClient, @Inject(MAT_DIALOG_DATA) public data: any, public dialogRef: MatDialogRef<UserAdministrationRedirectModalComponent>) {
    }

    setRedirectUser(user: any) {
        this.redirectUser = user;
    }
}
