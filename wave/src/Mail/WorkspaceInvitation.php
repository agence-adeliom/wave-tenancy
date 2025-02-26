<?php

namespace Wave\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Wave\WorkspaceInvitation as WorkspaceInvitationModel;

class WorkspaceInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The workspace invitation instance.
     *
     * @var WorkspaceInvitationModel
     */
    public WorkspaceInvitationModel $invitation;

    /**
     * Create a new message instance.
     *
     * @param  WorkspaceInvitationModel  $invitation
     * @return void
     */
    public function __construct(WorkspaceInvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('theme::emails.workspace-invitation', ['acceptUrl' => URL::signedRoute('wave.workspace-invitations.accept', [
            'invitation' => $this->invitation,
        ])])->subject(__('Workspace Invitation'));
    }
}
