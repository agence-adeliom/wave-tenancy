<?php
    use Filament\Forms\Components\TextInput;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware, name};
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use Filament\Notifications\Notification;
    middleware('auth');
    name('workspace.create');
    //can('create', Wave::newWorkspaceModel());

    new class extends Component implements HasForms
	{
        use InteractsWithForms;

        public ?array $data = [];

        public function mount(): void
        {
            $this->form->fill();
        }

        public function form(Form $form): Form
        {
            return $form
                ->schema([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required()
						->rules('required|string'),
                ])
                ->statePath('data');
        }

        public function save()
		{
			$state = $this->form->getState();
            $this->validate();

			//$this->saveFormFields($state);

			Notification::make()
                ->title('Successfully saved your profile settings')
                ->success()
                ->send();
		}

    }
?>

<x-layouts.app>
    @volt('workspace.create')
    <div class="relative">
        <x-app.workspace-layout
            title="Create Workspace"
            description="Create a new workspace to organize your project and teams."
            :hasSidebar="false"
        >
            <form wire:submit="save" class="w-full">
                {{ $this->form }}
                <div class="w-full pt-6 text-right">
                    <x-button type="submit">Save</x-button>
                </div>
            </form>
        </x-app.workspace-layout>
    </div>
    @endvolt
</x-layouts.app>
