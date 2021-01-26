import { Component, OnInit, ViewChild, Inject, TemplateRef, ViewContainerRef } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { HeaderService } from '@service/header.service';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { MatPaginator } from '@angular/material/paginator';
import { MatSidenav } from '@angular/material/sidenav';
import { MatSort } from '@angular/material/sort';
import { AppService } from '@service/app.service';
import { catchError, tap } from 'rxjs/operators';
import { of } from 'rxjs';

@Component({
    templateUrl: 'doctypes-administration.component.html'
})

export class DoctypesAdministrationComponent implements OnInit {

    @ViewChild('snav2', { static: true }) public sidenavRight: MatSidenav;
    @ViewChild('adminMenuTemplate', { static: true }) adminMenuTemplate: TemplateRef<any>;

    dialogRef: MatDialogRef<any>;
    config: any = {};

    doctypes: any[] = [];
    currentType: any = false;
    currentSecondLevel: any = false;
    currentFirstLevel: any = false;
    firstLevels: any = false;
    types: any = false;
    secondLevels: any = false;
    processModes: any = false;

    state: any;

    loading: boolean = false;
    creationMode: any = false;
    newSecondLevel: any = false;
    newFirstLevel: any = false;

    conservationRules: any = [];

    displayedColumns = ['label', 'use', 'mandatory', 'column'];
    @ViewChild(MatPaginator, { static: false }) paginator: MatPaginator;
    @ViewChild(MatSort, { static: false }) sort: MatSort;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        public dialog: MatDialog,
        private headerService: HeaderService,
        public appService: AppService,
        private viewContainerRef: ViewContainerRef
    ) { }

    ngOnInit(): void {
        this.headerService.setHeader(this.translate.instant('lang.administration') + ' ' + this.translate.instant('lang.documentTypes'));
        this.headerService.injectInSideBarLeft(this.adminMenuTemplate, this.viewContainerRef, 'adminMenu');

        this.loading = true;

        this.http.get('../rest/doctypes')
            .subscribe((data: any) => {
                this.doctypes = data['structure'];
                setTimeout(() => {
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
                            'multiple': false,
                            'data': this.doctypes,
                            'check_callback': function (operation: any, node: any, node_parent: any, node_position: any, more: any) {
                                if (operation === 'move_node') {
                                    if (typeof more.ref === 'undefined') {
                                        return true;
                                    }
                                    if (!isNaN(parseFloat(node.id)) && isFinite(node.id) && more.ref.id.indexOf('secondlevel_') === 0) {
                                        // Doctype in secondLevel
                                        if (more.ref.children.indexOf(node.id) > -1) {
                                            // same secondLevel
                                            return false;
                                        } else {
                                            return true;
                                        }
                                    } else if (node.id.indexOf('secondlevel_') === 0 && more.ref.id.indexOf('firstlevel_') === 0) {
                                        // SecondLevel in FirstLevel
                                        if (more.ref.children.indexOf(node.id) > -1) {
                                            // same FirstLevel
                                            return false;
                                        } else {
                                            return true;
                                        }
                                    } else {
                                        return false;
                                    }
                                }
                            }
                        },
                        'dnd': {
                            is_draggable: function (nodes: any) {
                                this.secondLevelSelected = nodes[0].id.replace('secondlevel_', '');
                                if ((!isNaN(parseFloat(this.secondLevelSelected)) && isFinite(this.secondLevelSelected)) ||
                                    (!isNaN(parseFloat(nodes[0].id)) && isFinite(nodes[0].id))) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        },
                        'plugins': ['search', 'dnd', 'contextmenu'],
                    });
                    let to: any = false;
                    $('#jstree_search').keyup(function () {
                        if (to) { clearTimeout(to); }
                        to = setTimeout(function () {
                            const v: any = $('#jstree_search').val();
                            $('#jstree').jstree(true).search(v);
                        }, 250);
                    });
                    $('#jstree')
                        // listen for event
                        .on('select_node.jstree', (e: any, item: any) => {
                            if (this.sidenavRight.opened === false) {
                                this.sidenavRight.open();
                            }
                            this.loadDoctype(item, false);

                        }).on('move_node.jstree', (e: any, item: any) => {
                            this.loadDoctype(item, true);
                        })
                        // create the instance
                        .jstree();
                }, 0);
                $('#jstree').jstree('select_node', this.doctypes[0]);
                this.loading = false;
            }, (err) => {
                this.notify.handleErrors(err);
            });
    }

    getRules() {
        return new Promise((resolve, reject) => {
            this.http.get('../rest/archival/retentionRules').pipe(
                tap((data: any) => {
                    if (data.retentionRules.length != 0) {
                        this.conservationRules = data.retentionRules;
                    } else {
                        this.conservationRules = [];
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

    loadDoctype(data: any, move: boolean) {
        this.creationMode = false;

        // Doctype
        if (data.node.original.type_id) {
            this.currentFirstLevel = false;
            this.currentSecondLevel = false;
            this.http.get('../rest/doctypes/types/' + data.node.original.type_id)
                .subscribe((dataValue: any) => {
                    this.currentType = dataValue['doctype'];
                    this.secondLevels = dataValue['secondLevel'];
                    this.processModes = ['NORMAL', 'SVA', 'SVR'];
                    this.getRules();

                    if (move) {
                        if (this.currentType) {
                            this.newSecondLevel = data.parent.replace('secondlevel_', '');
                            // Is integer
                            if (!isNaN(parseFloat(this.newSecondLevel)) && isFinite(this.newSecondLevel)) {
                                if (this.currentType.doctypes_second_level_id !== this.newSecondLevel) {
                                    this.currentType.doctypes_second_level_id = this.newSecondLevel;
                                    this.saveType();
                                }
                            } else {
                                alert(this.translate.instant('lang.cantMoveDoctype'));
                            }
                        } else {
                            alert(this.translate.instant('lang.noDoctypeSelected'));
                        }
                    }

                }, (err) => {
                    this.notify.error(err.error.errors);
                });

            // Second level
        } else if (data.node.original.doctypes_second_level_id) {
            this.currentFirstLevel = false;
            this.currentType = false;
            this.http.get('../rest/doctypes/secondLevel/' + data.node.original.doctypes_second_level_id)
                .subscribe((dataValue: any) => {
                    this.currentSecondLevel = dataValue['secondLevel'];
                    this.firstLevels = dataValue['firstLevel'];

                    if (move) {
                        if (this.currentSecondLevel) {
                            this.newFirstLevel = data.parent.replace('firstlevel_', '');
                            // Is integer
                            if (!isNaN(parseFloat(this.newFirstLevel)) && isFinite(this.newFirstLevel)) {
                                if (this.currentSecondLevel.doctypes_first_level_id !== this.newFirstLevel) {
                                    this.currentSecondLevel.doctypes_first_level_id = this.newFirstLevel;
                                    this.saveSecondLevel();
                                }
                            } else {
                                alert(this.translate.instant('lang.cantMoveFirstLevel'));
                            }
                        } else {
                            alert(this.translate.instant('lang.noFirstLevelSelected'));
                        }
                    }

                }, (err) => {
                    this.notify.error(err.error.errors);
                });

            // First level
        } else {
            this.currentSecondLevel = false;
            this.currentType = false;
            this.http.get('../rest/doctypes/firstLevel/' + data.node.original.doctypes_first_level_id)
                .subscribe((dataDoctypes: any) => {
                    this.currentFirstLevel = dataDoctypes['firstLevel'];
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    resetDatas() {
        this.currentFirstLevel = false;
        this.currentSecondLevel = false;
        this.currentType = false;
    }

    refreshTree() {
        $('#jstree').jstree(true).settings.core.data = this.doctypes;
        $('#jstree').jstree('refresh');
    }

    saveFirstLevel() {
        if (this.creationMode) {
            this.http.post('../rest/doctypes/firstLevel', this.currentFirstLevel)
                .subscribe((data: any) => {
                    this.resetDatas();
                    this.readMode();
                    this.doctypes = data['doctypeTree'];
                    this.refreshTree();
                    this.notify.success(this.translate.instant('lang.firstLevelAdded'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        } else {
            this.http.put('../rest/doctypes/firstLevel/' + this.currentFirstLevel.doctypes_first_level_id, this.currentFirstLevel)
                .subscribe((data: any) => {
                    this.doctypes = data['doctypeTree'];
                    this.refreshTree();
                    this.notify.success(this.translate.instant('lang.firstLevelUpdated'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    saveSecondLevel() {
        if (this.creationMode) {
            this.http.post('../rest/doctypes/secondLevel', this.currentSecondLevel)
                .subscribe((data: any) => {
                    this.resetDatas();
                    this.readMode();
                    this.doctypes = data['doctypeTree'];
                    this.refreshTree();
                    this.notify.success(this.translate.instant('lang.secondLevelAdded'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        } else {
            this.http.put('../rest/doctypes/secondLevel/' + this.currentSecondLevel.doctypes_second_level_id, this.currentSecondLevel)
                .subscribe((data: any) => {
                    this.doctypes = data['doctypeTree'];
                    this.refreshTree();
                    this.notify.success(this.translate.instant('lang.secondLevelUpdated'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    saveType() {
        if (this.creationMode) {
            this.http.post('../rest/doctypes/types', this.currentType)
                .subscribe((data: any) => {
                    this.resetDatas();
                    this.readMode();
                    this.doctypes = data['doctypeTree'];
                    this.refreshTree();
                    this.notify.success(this.translate.instant('lang.documentTypeAdded'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        } else {
            this.http.put('../rest/doctypes/types/' + this.currentType.type_id, this.currentType)
                .subscribe((data: any) => {
                    this.doctypes = data['doctypeTree'];
                    this.refreshTree();
                    this.notify.success(this.translate.instant('lang.documentTypeUpdated'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    readMode() {
        this.creationMode = false;
        $('#jstree').jstree('deselect_all');
        $('#jstree').jstree('select_node', this.doctypes[0]);
    }

    removeFirstLevel() {
        const r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.delete') + ' « ' + this.currentFirstLevel.doctypes_first_level_label + ' »');

        if (r) {
            this.http.delete('../rest/doctypes/firstLevel/' + this.currentFirstLevel.doctypes_first_level_id)
                .subscribe((data: any) => {
                    this.resetDatas();
                    this.readMode();
                    this.doctypes = data['doctypeTree'];
                    this.refreshTree();
                    if (this.doctypes[0]) {
                        $('#jstree').jstree('select_node', this.doctypes[0]);
                    } else if (this.sidenavRight.opened === true) {
                        this.sidenavRight.close();
                    }
                    this.notify.success(this.translate.instant('lang.firstLevelDeleted'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    removeSecondLevel() {
        const r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.delete') + ' « ' + this.currentSecondLevel.doctypes_second_level_label + ' »');

        if (r) {
            this.http.delete('../rest/doctypes/secondLevel/' + this.currentSecondLevel.doctypes_second_level_id)
                .subscribe((data: any) => {
                    this.resetDatas();
                    this.readMode();
                    this.doctypes = data['doctypeTree'];
                    this.refreshTree();
                    $('#jstree').jstree('select_node', this.doctypes[0]);
                    this.notify.success(this.translate.instant('lang.secondLevelDeleted'));
                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    removeType() {
        const r = confirm(this.translate.instant('lang.confirmAction') + ' ' + this.translate.instant('lang.delete') + ' « ' + this.currentType.description + ' »');

        if (r) {
            this.http.delete('../rest/doctypes/types/' + this.currentType.type_id)
                .subscribe((data: any) => {
                    if (data.deleted === 0) {
                        this.resetDatas();
                        this.readMode();
                        this.doctypes = data['doctypeTree'];
                        this.refreshTree();
                        $('#jstree').jstree('select_node', this.doctypes[0]);
                        this.notify.success(this.translate.instant('lang.documentTypeDeleted'));
                    } else {
                        this.config = { panelClass: 'maarch-modal', data: { count: data.deleted, types: data.doctypes } };
                        this.dialogRef = this.dialog.open(DoctypesAdministrationRedirectModalComponent, this.config);
                        this.dialogRef.afterClosed().subscribe((result: any) => {
                            if (result) {
                                this.http.put('../rest/doctypes/types/' + this.currentType.type_id + '/redirect', result)
                                    .subscribe((dataDoctypes: any) => {
                                        this.resetDatas();
                                        this.readMode();
                                        this.doctypes = dataDoctypes['doctypeTree'];
                                        this.refreshTree();
                                        $('#jstree').jstree('select_node', this.doctypes[0]);
                                        this.notify.success(this.translate.instant('lang.documentTypeDeleted'));
                                    }, (err) => {
                                        this.notify.error(err.error.errors);
                                    });
                            }
                            this.dialogRef = null;
                        });
                    }

                }, (err) => {
                    this.notify.error(err.error.errors);
                });
        }
    }

    prepareDoctypeAdd(mode: any) {
        this.currentFirstLevel = false;
        this.currentSecondLevel = false;
        this.currentType = false;
        if (mode === 'firstLevel') {
            this.currentFirstLevel = {};
        }
        if (mode === 'secondLevel') {
            this.currentSecondLevel = {};
        }
        if (mode === 'doctype') {
            this.currentType = {};
        }
        if (this.sidenavRight.opened === false) {
            this.sidenavRight.open();
        }
        $('#jstree').jstree('deselect_all');
        this.http.get('../rest/administration/doctypes/new')
            .subscribe((data: any) => {
                this.firstLevels = data['firstLevel'];
                this.secondLevels = data['secondLevel'];
                this.processModes = ['NORMAL', 'SVA', 'SVR'];
            }, (err) => {
                this.notify.error(err.error.errors);
            });
        this.creationMode = mode;
    }
}
@Component({
    templateUrl: 'doctypes-administration-redirect-modal.component.html'
})
export class DoctypesAdministrationRedirectModalComponent {
    

    constructor(public http: HttpClient, @Inject(MAT_DIALOG_DATA) public data: any, public dialogRef: MatDialogRef<DoctypesAdministrationRedirectModalComponent>) {

    }
}
