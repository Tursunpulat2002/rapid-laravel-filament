<?php

namespace App\Livewire;

use App\Models\Attendee;
use Filament\{Actions\Action,
    Actions\Concerns\InteractsWithActions,
    Actions\Contracts\HasActions,
    Forms\Components\Placeholder,
    Forms\Components\Repeater,
    Forms\Concerns\InteractsWithForms,
    Forms\Contracts\HasForms,
    Forms\Get,
    Notifications\Notification};
use Illuminate\Support\HtmlString;
use Livewire\Component;

class ConferenceSignUpPage extends Component implements HasForms, HasActions
{
    use InteractsWithActions, InteractsWithForms;

    public int $conferenceId;
    public int $price = 50000;

    public function mount()
    {
        $this->conferenceId = 1;
    }

    public function signUpAction(): Action
    {
        return Action::make('signUp')
            ->slideOver()
            ->form([
                Placeholder::make('total_price')
                    ->hiddenLabel()
                    ->content(function (Get $get) {
                        return '$' . count($get('attendees')) * 500;
                    }),
                Repeater::make('attendees')
                    ->schema(Attendee::getForm()),
            ])
            ->action(function (array $data){
                collect($data['attendees'])->each(function ($data){
                    Attendee::create([
                        'conference_id' => $this->conferenceId,
                        'ticket_cost' => $this->price,
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'is_paid' => true,
                    ]);
                });
            })
            ->after(function () {
                Notification::make()->success()->title('Success!')
                    ->body(new HtmlString('You have successfully signed up.'))
                    ->send();
            });
    }

    public function render()
    {
        return view('livewire.conference-sign-up-page');
    }
}
