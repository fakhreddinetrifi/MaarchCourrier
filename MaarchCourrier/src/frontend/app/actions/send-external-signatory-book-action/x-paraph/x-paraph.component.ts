import { Component, OnInit, Input } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NotificationService } from '@service/notification/notification.service';
import { HttpClient } from '@angular/common/http';
import { FormControl } from '@angular/forms';
import { startWith, map } from 'rxjs/operators';
import { CdkDragDrop, moveItemInArray } from '@angular/cdk/drag-drop';
import { Observable } from 'rxjs';

declare var $: any;

@Component({
    selector: 'app-x-paraph',
    templateUrl: 'x-paraph.component.html',
    styleUrls: ['x-paraph.component.scss'],
})
export class XParaphComponent implements OnInit {

    loading: boolean = false;

    newAccount: any = {};
    currentAccount: any = null;
    usersWorkflowList: any[] = [];
    currentWorkflow: any[] = [];
    contextList = ['FON', 'PER', 'SPH', 'DIR', 'DLP', 'EXE'];
    addAccountMode: boolean = false;

    usersCtrl = new FormControl();
    filteredUsers: Observable<any[]>;

    @Input() additionalsInfos: any;
    @Input() externalSignatoryBookDatas: any;

    constructor(public translate: TranslateService, public http: HttpClient, private notify: NotificationService) { }

    ngOnInit(): void { }

    drop(event: CdkDragDrop<string[]>) {
        if (event.previousContainer === event.container) {
            moveItemInArray(event.container.data, event.previousIndex, event.currentIndex);
        }
    }

    selectAccount(account: any) {
        this.loading = false;
        this.currentAccount = account.value;
        this.usersWorkflowList = [];
        this.currentWorkflow = [];
        this.getUsersWorkflowList(this.currentAccount);
    }

    getUsersWorkflowList(account: any) {
        this.loading = true;
        this.filteredUsers = this.usersCtrl.valueChanges
            .pipe(
                startWith(''),
                map(state => state ? this._filterUsers(state) : this.usersWorkflowList.slice())
            );
        this.http.get('../rest/xParaphWorkflow?login=' + account.login + '&siret=' + account.siret)
            .subscribe((data: any) => {
                this.usersWorkflowList = data.workflow;
                this.usersWorkflowList.forEach(element => {
                    element.currentRole = element.roles[0];
                    element.currentContext = this.contextList[0];
                });
                setTimeout(() => {
                    $('#availableUsers').focus();
                }, 0);

            }, (err: any) => {
                this.loading = false;
                this.notify.error(err.error.errors[0]);
            });
    }

    changeRole(i: number, role: string) {
        this.currentWorkflow[i].currentRole = role;
    }

    changeContext(i: number, contextId: string) {
        this.currentWorkflow[i].currentContext = contextId;
    }

    addItem(event: any) {
        this.currentWorkflow.push(JSON.parse(JSON.stringify(event.option.value)));
        $('#availableUsers').blur();
        this.usersCtrl.setValue('');
    }

    deleteItem(index: number) {
        this.currentWorkflow.splice(index, 1);
    }

    private _filterUsers(value: string): any[] {

        if (typeof value === 'string') {
            const filterValue = value.toLowerCase();
            return this.usersWorkflowList.filter(user => user.displayName.toLowerCase().indexOf(filterValue) !== -1);
        }
    }

    isValidParaph() {
        if (this.additionalsInfos.attachments.length > 0 && this.currentWorkflow.length > 0 && this.currentAccount.login !== '' && this.currentAccount.siret !== '') {
            return true;
        } else {
            return false;
        }
    }

    getRessources() {
        return this.additionalsInfos.attachments.map((e: any) => e.res_id);
    }

    getDatas() {
        this.externalSignatoryBookDatas = {
            'info': {
                'siret': this.currentAccount.siret,
                'login': this.currentAccount.login
            },
            'steps': []
        };
        this.currentWorkflow.forEach(element => {
            this.externalSignatoryBookDatas.steps.push({
                'login': element.userId,
                'action': element.currentRole === 'visa' ? '2' : '1',
                'contexte': element.currentContext
            });
        });
        return this.externalSignatoryBookDatas;
    }

    addNewAccount() {
        this.loading = true;

        this.http.post('../rest/xParaphAccount', { login: this.newAccount.login, siret: this.newAccount.siret })
            .subscribe((data: any) => {
                this.additionalsInfos.accounts.push({
                    'login': this.newAccount.login,
                    'siret': this.newAccount.siret,
                });
                this.newAccount = {};
                this.loading = false;
                this.addAccountMode = false;

                this.notify.success(this.translate.instant('lang.accountAdded'));
            }, (err: any) => {
                this.notify.handleErrors(err);
                this.loading = false;
            });
    }

    removeAccount(index: number) {
        const r = confirm(this.translate.instant('lang.confirmDeleteAccount'));

        if (r) {
            this.http.delete('../rest/xParaphAccount?siret=' + this.additionalsInfos.accounts[index].siret + '&login=' + this.additionalsInfos.accounts[index].login)
                .subscribe((data: any) => {
                    this.additionalsInfos.accounts.splice(index, 1);
                    this.notify.success(this.translate.instant('lang.accountDeleted'));
                }, (err: any) => {
                    this.notify.handleErrors(err);
                    this.loading = false;
                });
        }
    }

    initNewAccount() {
        this.loading = false;
        this.usersWorkflowList = [];
        this.currentWorkflow = [];
        this.currentAccount = null;
        this.addAccountMode = true;
        setTimeout(() => {
            $('#newAccountLogin').focus();
        }, 0);

    }
}
