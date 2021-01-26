import { NgModule, Injectable } from '@angular/core';

import { SharedModule } from './app-common.module';
import { AppRoutingModule } from './app-routing.module';

import { AdministrationModule } from './administration/administration.module';

import { BrowserModule, HammerGestureConfig, HAMMER_GESTURE_CONFIG } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { InternationalizationModule } from '@service/translate/internationalization.module';

import { JoyrideModule } from 'ngx-joyride';

import { PanelListComponent } from './list/panel/panel-list.component';
import { DocumentViewerModule } from './viewer/document-viewer.module';
import { AppListModule } from './app-list.module';

import { AuthInterceptor } from '@service/auth-interceptor.service';
import { FiltersListService } from '@service/filtersList.service';
import { CriteriaSearchService } from '@service/criteriaSearch.service';
import { FoldersService } from './folder/folders.service';
import { PrivilegeService } from '@service/privileges.service';
import { ActionsService } from './actions/actions.service';

import { AppComponent } from './app.component';

// ACTIONS
import { ConfirmActionComponent } from './actions/confirm-action/confirm-action.component';
import { DisabledBasketPersistenceActionComponent } from './actions/disabled-basket-persistence-action/disabled-basket-persistence-action.component';
import { EnabledBasketPersistenceActionComponent } from './actions/enabled-basket-persistence-action/enabled-basket-persistence-action.component';
import { ResMarkAsReadActionComponent } from './actions/res-mark-as-read-action/res-mark-as-read-action.component';
import { CloseMailActionComponent } from './actions/close-mail-action/close-mail-action.component';
import { RejectVisaBackToPrevousActionComponent } from './actions/visa-reject-back-to-previous-action/reject-visa-back-to-previous-action.component';
import { ResetVisaActionComponent } from './actions/visa-reset-action/reset-visa-action.component';
import { InterruptVisaActionComponent } from './actions/visa-interrupt-action/interrupt-visa-action.component';
import { UpdateAcknowledgementSendDateActionComponent } from './actions/update-acknowledgement-send-date-action/update-acknowledgement-send-date-action.component';
import { CreateAcknowledgementReceiptActionComponent } from './actions/create-acknowledgement-receipt-action/create-acknowledgement-receipt-action.component';
import { CloseAndIndexActionComponent } from './actions/close-and-index-action/close-and-index-action.component';
import { UpdateDepartureDateActionComponent } from './actions/update-departure-date-action/update-departure-date-action.component';
import { SendExternalSignatoryBookActionComponent } from './actions/send-external-signatory-book-action/send-external-signatory-book-action.component';
import { SendExternalNoteBookActionComponent } from './actions/send-external-note-book-action/send-external-note-book-action.component';
import { XParaphComponent } from './actions/send-external-signatory-book-action/x-paraph/x-paraph.component';
import { MaarchParaphComponent } from './actions/send-external-signatory-book-action/maarch-paraph/maarch-paraph.component';
import { IParaphComponent } from './actions/send-external-signatory-book-action/i-paraph/i-paraph.component';
import { IxbusParaphComponent } from './actions/send-external-signatory-book-action/ixbus-paraph/ixbus-paraph.component';
import { FastParaphComponent } from './actions/send-external-signatory-book-action/fast-paraph/fast-paraph.component';
import { ViewDocActionComponent } from './actions/view-doc-action/view-doc-action.component';
import { RedirectActionComponent } from './actions/redirect-action/redirect-action.component';
import { SendShippingActionComponent } from './actions/send-shipping-action/send-shipping-action.component';
import { redirectInitiatorEntityActionComponent } from './actions/redirect-initiator-entity-action/redirect-initiator-entity-action.component';
import { closeMailWithAttachmentsOrNotesActionComponent } from './actions/close-mail-with-attachments-or-notes-action/close-mail-with-attachments-or-notes-action.component';
import { SendSignatureBookActionComponent } from './actions/visa-send-signature-book-action/send-signature-book-action.component';
import { ContinueVisaCircuitActionComponent } from './actions/visa-continue-circuit-action/continue-visa-circuit-action.component';
import { ContinueAvisCircuitActionComponent } from './actions/avis-continue-circuit-action/continue-avis-circuit-action.component';
import { SendAvisWorkflowComponent } from './actions/avis-workflow-send-action/send-avis-workflow-action.component';
import { SendAvisParallelComponent } from './actions/avis-parallel-send-action/send-avis-parallel-action.component';
import { GiveAvisParallelActionComponent } from './actions/avis-give-parallel-action/give-avis-parallel-action.component';
import { ValidateAvisParallelComponent } from './actions/avis-parallel-validate-action/validate-avis-parallel-action.component';
import { ReconcileActionComponent } from './actions/reconciliation-action/reconcile-action.component';
import { SendAlfrescoActionComponent } from './actions/send-alfresco-action/send-alfresco-action.component';
import { SaveRegisteredMailActionComponent } from './actions/save-registered-mail-action/save-registered-mail-action.component';
import { SaveAndIndexRegisteredMailActionComponent } from './actions/save-and-index-registered-mail-action/save-and-index-registered-mail-action.component';
import { SaveAndPrintRegisteredMailActionComponent } from './actions/save-and-print-registered-mail-action/save-and-print-registered-mail-action.component';
import { PrintRegisteredMailActionComponent } from './actions/print-registered-mail-action/print-registered-mail-action.component';
import { PrintDepositListActionComponent } from './actions/print-deposit-list-action/print-deposit-list-action.component';
import { SendToRecordManagementComponent } from './actions/send-to-record-management-action/send-to-record-management.component';
import { CheckReplyRecordManagementComponent } from './actions/check-reply-record-management-action/check-reply-record-management.component';
import { ResetRecordManagementComponent } from './actions/reset-record-management-action/reset-record-management.component';
import { CheckAcknowledgmentRecordManagementComponent } from './actions/check-acknowledgment-record-management-action/check-acknowledgment-record-management.component';


// PROCESS
import { ProcessComponent } from './process/process.component';
import { IndexationComponent } from './indexation/indexation.component';
import { LinkResourceModalComponent } from './linkedResource/linkResourceModal/link-resource-modal.component';
import { HistoryWorkflowResumeComponent } from './history/history-workflow-resume/history-workflow-resume.component';
import { NoteResumeComponent } from './notes/note-resume/note-resume.component';
import { AttachmentsResumeComponent } from './attachments/attachments-resume/attachments-resume.component';
import { MailResumeComponent } from './mail/mail-resume/mail-resume.component';
import { SentResourceListComponent } from './sentResource/sent-resource-list.component';
import { SentResourcePageComponent } from './sentResource/sent-resource-page/sent-resource-page.component';
import { SentNumericPackagePageComponent } from './sentResource/sent-numeric-package-page/sent-numeric-package-page.component';
import { ThesaurusModalComponent } from './tag/indexing/thesaurus/thesaurus-modal.component';
import { SelectIndexingModelComponent } from './indexation/select-indexing-model/select-indexing-model.component';
import { FilterToolComponent } from './search/filter-tool/filter-tool.component';
import { TechnicalInformationComponent } from './indexation/technical-information/technical-information.component';

import { SearchComponent } from './search/search.component';
import { SearchResultListComponent } from './search/result-list/search-result-list.component';
import { AboutUsComponent } from './about-us.component';
import { ActivateUserComponent } from './activate-user.component';
import { AddAvisModelModalComponent } from './avis/addAvisModel/add-avis-model-modal.component';
import { AddPrivateIndexingModelModalComponent } from './indexation/private-indexing-model/add-private-indexing-model-modal.component';
import { AddSearchTemplateModalComponent } from './search/criteria-tool/search-template/search-template-modal.component';
import { AddVisaModelModalComponent } from './visa/addVisaModel/add-visa-model-modal.component';
import { AttachmentCreateComponent } from './attachments/attachment-create/attachment-create.component';
import { AttachmentPageComponent } from './attachments/attachments-page/attachment-page.component';
import { BasketListComponent } from './list/basket-list.component';
import { ContactModalComponent } from './administration/contact/modal/contact-modal.component';
import { ContactResourceModalComponent } from './contact/contact-resource/modal/contact-resource-modal.component';
import { DocumentViewerPageComponent } from './viewer/page/document-viewer-page.component';
import { FolderCreateModalComponent } from './folder/folder-create-modal/folder-create-modal.component';
import { FolderDocumentListComponent } from './folder/document-list/folder-document-list.component';
import { FolderPinnedComponent } from './folder/folder-pinned/folder-pinned.component';
import { FolderTreeComponent } from './folder/folder-tree.component';
import { FolderUpdateComponent } from './folder/folder-update/folder-update.component';
import { FollowedDocumentListComponent } from './home/followed-list/followed-document-list.component';
import { ForgotPasswordComponent } from './login/forgotPassword/forgotPassword.component';
import { HomeComponent } from './home/home.component';
import { LoginComponent } from './login/login.component';
import { MaarchParapheurListComponent } from './home/maarch-parapheur/maarch-parapheur-list.component';
import { PanelFolderComponent } from './folder/panel/panel-folder.component';
import { PasswordModificationComponent, InfoChangePasswordModalComponent, } from './login/passwordModification/password-modification.component';
import { PrintSeparatorComponent } from './separator/print-separator/print-separator.component';
import { ProfileComponent } from './profile.component';
import { RedirectIndexingModelComponent } from './administration/indexingModel/redirectIndexingModel/redirect-indexing-model.component';
import { ResetPasswordComponent } from './login/resetPassword/reset-password.component';
import { SaveNumericPackageComponent } from './save-numeric-package.component';
import { SignatureBookComponent } from './signature-book.component';
import { VisaWorkflowModalComponent } from './visa/modal/visa-workflow-modal.component';

import { DevToolComponent } from '@service/debug/dev-tool.component';
import { DevLangComponent } from '@service/debug/dev-lang.component';
import { AcknowledgementReceptionComponent } from './registeredMail/acknowledgement-reception/acknowledgement-reception.component';
import { DatePipe } from '@angular/common';

@Injectable()
export class MyHammerConfig extends HammerGestureConfig {
    overrides = <any>{
        'pinch': { enable: false },
        'rotate': { enable: false }
    };
}

@NgModule({
    imports: [
        BrowserModule,
        BrowserAnimationsModule,
        HttpClientModule,
        InternationalizationModule,
        JoyrideModule.forRoot(),
        SharedModule,
        AppRoutingModule,
        AdministrationModule,
        DocumentViewerModule,
        AppListModule,
    ],
    declarations: [
        AppComponent,
        ProcessComponent,
        IndexationComponent,
        LinkResourceModalComponent,
        HistoryWorkflowResumeComponent,
        NoteResumeComponent,
        AttachmentsResumeComponent,
        MailResumeComponent,
        SentResourceListComponent,
        SentResourcePageComponent,
        SentNumericPackagePageComponent,
        ThesaurusModalComponent,
        SelectIndexingModelComponent,
        FilterToolComponent,
        PanelListComponent,
        SearchComponent,
        SearchResultListComponent,
        AboutUsComponent,
        ActivateUserComponent,
        AddAvisModelModalComponent,
        AddPrivateIndexingModelModalComponent,
        AddSearchTemplateModalComponent,
        AddVisaModelModalComponent,
        AttachmentCreateComponent,
        AttachmentPageComponent,
        BasketListComponent,
        ContactModalComponent,
        ContactResourceModalComponent,
        DocumentViewerPageComponent,
        FolderCreateModalComponent,
        FolderDocumentListComponent,
        FolderPinnedComponent,
        FolderTreeComponent,
        FolderUpdateComponent,
        FollowedDocumentListComponent,
        ForgotPasswordComponent,
        HomeComponent,
        InfoChangePasswordModalComponent,
        LoginComponent,
        MaarchParapheurListComponent,
        PanelFolderComponent,
        PasswordModificationComponent,
        PrintSeparatorComponent,
        ProfileComponent,
        RedirectIndexingModelComponent,
        ResetPasswordComponent,
        SaveNumericPackageComponent,
        SignatureBookComponent,
        VisaWorkflowModalComponent,
        DevLangComponent,
        DevToolComponent,
        AcknowledgementReceptionComponent,
        ConfirmActionComponent,
        ResMarkAsReadActionComponent,
        EnabledBasketPersistenceActionComponent,
        DisabledBasketPersistenceActionComponent,
        CloseAndIndexActionComponent,
        UpdateAcknowledgementSendDateActionComponent,
        CreateAcknowledgementReceiptActionComponent,
        CloseMailActionComponent,
        RejectVisaBackToPrevousActionComponent,
        ResetVisaActionComponent,
        InterruptVisaActionComponent,
        UpdateDepartureDateActionComponent,
        SendExternalSignatoryBookActionComponent,
        SendExternalNoteBookActionComponent,
        XParaphComponent,
        MaarchParaphComponent,
        FastParaphComponent,
        IxbusParaphComponent,
        IParaphComponent,
        ViewDocActionComponent,
        RedirectActionComponent,
        SendShippingActionComponent,
        redirectInitiatorEntityActionComponent,
        closeMailWithAttachmentsOrNotesActionComponent,
        SendSignatureBookActionComponent,
        ContinueVisaCircuitActionComponent,
        ContinueAvisCircuitActionComponent,
        SendAvisWorkflowComponent,
        SendAvisParallelComponent,
        GiveAvisParallelActionComponent,
        ValidateAvisParallelComponent,
        SendAlfrescoActionComponent,
        SaveRegisteredMailActionComponent,
        SaveAndPrintRegisteredMailActionComponent,
        SaveAndIndexRegisteredMailActionComponent,
        PrintRegisteredMailActionComponent,
        PrintDepositListActionComponent,
        ReconcileActionComponent,
        SendToRecordManagementComponent,
        CheckReplyRecordManagementComponent,
        ResetRecordManagementComponent,
        CheckAcknowledgmentRecordManagementComponent,
        TechnicalInformationComponent
    ],
    exports : [
        SharedModule
    ],
    entryComponents: [
        LinkResourceModalComponent,
        SentResourcePageComponent,
        SentNumericPackagePageComponent,
        ThesaurusModalComponent,
        AddAvisModelModalComponent,
        AddPrivateIndexingModelModalComponent,
        AddSearchTemplateModalComponent,
        AddVisaModelModalComponent,
        AttachmentCreateComponent,
        AttachmentPageComponent,
        ContactModalComponent,
        ContactResourceModalComponent,
        FolderCreateModalComponent,
        FolderUpdateComponent,
        InfoChangePasswordModalComponent,
        RedirectIndexingModelComponent,
        VisaWorkflowModalComponent,
        DevLangComponent,
        ConfirmActionComponent,
        ResMarkAsReadActionComponent,
        EnabledBasketPersistenceActionComponent,
        DisabledBasketPersistenceActionComponent,
        CloseAndIndexActionComponent,
        UpdateAcknowledgementSendDateActionComponent,
        CreateAcknowledgementReceiptActionComponent,
        CloseMailActionComponent,
        RejectVisaBackToPrevousActionComponent,
        ResetVisaActionComponent,
        InterruptVisaActionComponent,
        SendAvisWorkflowComponent,
        SendAvisParallelComponent,
        UpdateDepartureDateActionComponent,
        SendExternalSignatoryBookActionComponent,
        SendExternalNoteBookActionComponent,
        ContinueVisaCircuitActionComponent,
        ContinueAvisCircuitActionComponent,
        GiveAvisParallelActionComponent,
        ValidateAvisParallelComponent,
        RedirectActionComponent,
        SendShippingActionComponent,
        redirectInitiatorEntityActionComponent,
        closeMailWithAttachmentsOrNotesActionComponent,
        SendSignatureBookActionComponent,
        SendAlfrescoActionComponent,
        SaveRegisteredMailActionComponent,
        SaveAndPrintRegisteredMailActionComponent,
        SaveAndIndexRegisteredMailActionComponent,
        PrintRegisteredMailActionComponent,
        PrintDepositListActionComponent,
        ViewDocActionComponent,
        ReconcileActionComponent,
        TechnicalInformationComponent
    ],
    providers: [
        { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true },
        DatePipe,
        FiltersListService,
        CriteriaSearchService,
        FoldersService,
        ActionsService,
        PrivilegeService,
        {
            provide: HAMMER_GESTURE_CONFIG,
            useClass: MyHammerConfig
        }
    ],
    bootstrap: [AppComponent]
})
export class AppModule { }
