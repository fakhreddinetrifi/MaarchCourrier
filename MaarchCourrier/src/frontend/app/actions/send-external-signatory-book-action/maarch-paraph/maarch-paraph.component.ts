import { Component, OnInit, Input, ViewChild } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { HttpClient } from '@angular/common/http';
import { VisaWorkflowComponent } from '../../../visa/visa-workflow.component';

@Component({
    selector: 'app-maarch-paraph',
    templateUrl: 'maarch-paraph.component.html',
    styleUrls: ['maarch-paraph.component.scss'],
})
export class MaarchParaphComponent implements OnInit {

    
    loading: boolean = false;

    currentAccount: any = null;
    usersWorkflowList: any[] = [];

    injectDatasParam = {
        resId: 0,
        editable: true
    };

    @ViewChild('appVisaWorkflow', { static: false }) appVisaWorkflow: VisaWorkflowComponent;

    @Input() additionalsInfos: any;
    @Input() externalSignatoryBookDatas: any;

    constructor(public translate: TranslateService, public http: HttpClient) { }

    ngOnInit(): void {
        if (typeof this.additionalsInfos.destinationId !== 'undefined' && this.additionalsInfos.destinationId !== '') {
            setTimeout(() => {
                this.appVisaWorkflow.loadListModel(this.additionalsInfos.destinationId);
            }, 0);
        }
    }

    isValidParaph() {
        if (this.additionalsInfos.attachments.length === 0 || this.appVisaWorkflow.getWorkflow().length === 0 || this.appVisaWorkflow.checkExternalSignatoryBook().length > 0) {
            return false;
        } else {
            return true;
        }
    }

    getRessources() {
        return this.additionalsInfos.attachments.map((e: any) => e.res_id);
    }

    getDatas() {
        const workflow = this.appVisaWorkflow.getWorkflow();

        workflow.forEach((element: any) => {
            this.externalSignatoryBookDatas.steps.push(
                {
                    'externalId': element.externalId.maarchParapheur,
                    'action': element.requested_signature ? 'sign' : 'visa',
                }
            );
        });

        return this.externalSignatoryBookDatas;
    }
}
