import { Component, OnInit, Inject, ViewChild } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { HttpClient } from '@angular/common/http';
import { DiffusionsListComponent } from '../../diffusions/diffusions-list.component';
import { FormControl } from '@angular/forms';
import { map, tap, finalize, catchError, startWith } from 'rxjs/operators';
import { NoteEditorComponent } from '../../notes/note-editor.component';
import { FunctionsService } from '@service/functions.service';
import { Observable, of } from 'rxjs';

declare var $: any;

@Component({
    templateUrl: 'redirect-action.component.html',
    styleUrls: ['redirect-action.component.scss'],
})
export class RedirectActionComponent implements OnInit {

    loading: boolean = false;

    entities: any[] = [];
    injectDatasParam = {
        resId: 0,
        editable: true,
        keepDestForRedirection: false
    };
    destUser: any = null;
    oldUser: any = null;
    keepDestForRedirection: boolean = false;
    currentDiffusionListDestRedirect: any = [];
    diffusionListDestRedirect: any = [];
    currentEntity: any = {
        'serialId': 0,
        'entity_label': ''
    };
    redirectMode = '';
    userListRedirect: any[] = [];
    userRedirectCtrl = new FormControl();
    filteredUserRedirect: Observable<any[]>;
    isDestinationChanging: boolean = false;

    @ViewChild('appDiffusionsList', { static: false }) appDiffusionsList: DiffusionsListComponent;
    @ViewChild('noteEditor', { static: false }) noteEditor: NoteEditorComponent;

    constructor(
        public translate: TranslateService,
        public http: HttpClient,
        private notify: NotificationService,
        public dialogRef: MatDialogRef<RedirectActionComponent>,
        @Inject(MAT_DIALOG_DATA) public data: any,
        private functionsService: FunctionsService
    ) { }

    async ngOnInit(): Promise<void> {

        this.loading = true;

        await this.getEntities();
        await this.getDefaultEntity();

        if (this.userListRedirect.length === 0 && this.entities.filter((entity: any) => entity.allowed).length === 0) {
            this.redirectMode = 'none';
            this.loading = false;
        } else if (this.userListRedirect.length === 0 && this.entities.filter((entity: any) => entity.allowed).length > 0) {
            this.loadEntities();
        } else if (this.userListRedirect.length > 0 && this.entities.filter((entity: any) => entity.allowed).length === 0) {
            this.loadDestUser();
        } else {
            this.loading = false;
        }
    }

    getEntities() {
        return new Promise((resolve, reject) => {
            this.http.get(`../rest/resourcesList/users/${this.data.userId}/groups/${this.data.groupId}/baskets/${this.data.basketId}/actions/${this.data.action.id}/getRedirect`).pipe(
                tap((data: any) => {
                    this.entities = data['entities'];
                    this.userListRedirect = data.users;
                    this.keepDestForRedirection = data.keepDestForRedirection;
                    this.injectDatasParam.keepDestForRedirection = data.keepDestForRedirection;

                    resolve(true);
                }),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    return of(false);
                })
            ).subscribe();
        });
    }

    getDefaultEntity() {
        return new Promise((resolve, reject) => {
            if (this.data.resIds.length === 1) {
                this.http.get(`../rest/resources/${this.data.resIds[0]}/fields/destination?alt=true`).pipe(
                    tap((data: any) => {
                        if (!this.functionsService.empty(data.field)) {
                            this.currentEntity = this.entities.filter((entity: any) => entity.serialId === data.field)[0];
                            this.entities = this.entities.map((entity: any) => {
                                return {
                                    ...entity,
                                    state : {
                                        selected : false,
                                        opened: false,
                                        disabled: entity.state.disabled
                                    }
                                };
                            });
                        } else {
                            if (this.entities.filter((entity: any) => entity.state.selected).length > 0) {
                                this.currentEntity = this.entities.filter((entity: any) => entity.state.selected)[0];
                            }
                        }
                        resolve(true);
                    }),
                    catchError((err: any) => {
                        this.notify.handleSoftErrors(err);
                        return of(false);
                    })
                ).subscribe();
            } else {
                this.currentEntity = this.entities.filter((entity: any) => entity.state.selected)[0];
                resolve(true);
            }
        });
    }

    loadEntities() {
        this.redirectMode = 'entity';
        if (this.data.resIds.length === 1) {
            this.injectDatasParam.resId = this.data.resIds[0];
        }
        this.loading = false;
        setTimeout(() => {
            $('#jstree').jstree({
                'checkbox': {
                    'deselect_all': true,
                    'three_state': false // no cascade selection
                },
                'core': {
                    force_text: true,
                    'themes': {
                        'name': 'proton',
                        'responsive': true
                    },
                    'multiple': false,
                    'data': this.entities,
                },
                'plugins': ['checkbox', 'search', 'sort']
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
                .on('loaded.jstree', (e: any, data: any) => {
                    if (this.currentEntity.serialId > 0) {
                        $('#jstree').jstree('select_node', this.currentEntity);
                        this.selectEntity(this.currentEntity, true);
                    }
                }).on('select_node.jstree', (e: any, data: any) => {
                    this.selectEntity(data.node.original);

                }).on('deselect_node.jstree', (e: any, data: any) => {
                    $('#jstree').jstree('select_node', data.node.original);
                })
                // create the instance
                .jstree();
        }, 0);
    }

    loadDestUser() {
        this.redirectMode = 'user';
        this.filteredUserRedirect = this.userRedirectCtrl.valueChanges
            .pipe(
                startWith(''),
                map(user => user ? this._filterUserRedirect(user) : this.userListRedirect.slice())
            );

        this.loading = false;
        if (this.data.resIds.length === 1) {
            this.http.get('../rest/resources/' + this.data.resIds[0] + '/listInstance').subscribe((data: any) => {
                this.diffusionListDestRedirect = data.listInstance;
                Object.keys(data).forEach(diffusionRole => {
                    data[diffusionRole].forEach((line: any) => {
                        if (line.item_mode === 'dest') {
                            this.oldUser = line;
                        }
                    });
                });
                $('.searchUserRedirect').click();
            }, (err: any) => {
                this.notify.handleErrors(err);
            });
        } else {
            this.keepDestForRedirection = false;
            setTimeout(() => {
                $('.searchUserRedirect').click();
            }, 200);
        }
    }

    changeDest(event: any) {
        this.currentDiffusionListDestRedirect = this.diffusionListDestRedirect;
        const user = event.option.value;

        this.destUser = {
            difflist_type: 'entity_id',
            item_mode: 'dest',
            item_type: 'user_id',
            item_id: user.user_id,
            labelToDisplay: user.labelToDisplay,
            descriptionToDisplay: user.descriptionToDisplay
        };

        if (this.data.resIds.length === 1) {
            this.isDestinationChanging = false;
            this.http.get('../rest/resources/' + this.data.resIds[0] + '/users/' + user.id + '/isDestinationChanging')
                .subscribe((data: any) => {
                    this.isDestinationChanging = data.isDestinationChanging;
                }, (err: any) => {
                    this.notify.handleErrors(err);
                });

            if (this.keepDestForRedirection && this.currentDiffusionListDestRedirect.length > 0) {
                let isInCopy = false;
                let newCopy = null;
                this.currentDiffusionListDestRedirect.forEach((element: any) => {
                    if (element.item_mode === 'cc' && element.item_id === this.oldUser.item_id) {
                        isInCopy = true;
                    }
                });

                if (!isInCopy) {
                    newCopy = this.oldUser;
                    newCopy.item_mode = 'cc';
                    this.currentDiffusionListDestRedirect.push(newCopy);
                }
            }
            this.currentDiffusionListDestRedirect.splice(this.currentDiffusionListDestRedirect.map((e: any) => e.item_mode).indexOf('dest'), 1);
        } else {
            this.isDestinationChanging = true;
        }
        this.currentDiffusionListDestRedirect.push(this.destUser);

        this.userRedirectCtrl.reset();
        $('.searchUserRedirect').blur();
    }

    private _filterUserRedirect(value: string): any[] {
        if (typeof value === 'string') {
            const filterValue = value.toLowerCase();
            return this.userListRedirect.filter(user => user.labelToDisplay.toLowerCase().indexOf(filterValue) >= 0);
        }
    }

    selectEntity(entity: any, initLoad: boolean = false) {
        this.currentEntity = entity;
        this.appDiffusionsList.loadListModel(entity.serialId, initLoad);
    }

    onSubmit() {
        this.loading = true;
        if (this.data.resIds.length > 0) {
            this.executeAction();
        }
    }

    executeAction() {
        if (this.redirectMode === 'user') {
            this.http.put(this.data.processActionRoute, { resources: this.data.resIds, data: { onlyRedirectDest: true, listInstances: this.currentDiffusionListDestRedirect }, note: this.noteEditor.getNote() }).pipe(
                tap((data: any) => {
                    if (data && data.errors != null) {
                        this.notify.error(data.errors);
                    }
                    this.dialogRef.close(this.data.resIds);
                }),
                finalize(() => this.loading = false),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    return of(false);
                })
            ).subscribe();
        } else {

            this.http.put(this.data.processActionRoute, { resources: this.data.resIds, data: { listInstances: this.appDiffusionsList.getCurrentListinstance() }, note: this.noteEditor.getNote() }).pipe(
                tap((data: any) => {
                    if (data && data.errors != null) {
                        this.notify.error(data.errors);
                    }
                    this.dialogRef.close(this.data.resIds);
                }),
                finalize(() => this.loading = false),
                catchError((err: any) => {
                    this.notify.handleSoftErrors(err);
                    return of(false);
                })
            ).subscribe();
        }
    }

    checkValidity() {
        if (this.redirectMode === 'entity' && this.appDiffusionsList && this.appDiffusionsList.getDestUser().length > 0 && this.currentEntity.serialId > 0 && !this.loading) {
            return false;
        } if (this.redirectMode === 'user' && this.currentDiffusionListDestRedirect.length > 0 && this.destUser != null && !this.loading) {
            return false;
        } else {
            return true;
        }
    }
}
