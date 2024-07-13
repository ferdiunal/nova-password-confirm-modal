# Nova Password Confirm Modal

This Laravel Nova field manages the availability of data by requiring a password confirmation through a modal dialog.

## Features

- **Secure Data Access**: Ensures that sensitive operations require password confirmation.
- **Integration with Laravel Nova**: Seamlessly integrates as a field within Laravel Nova panels.

## Installation

To install the package, run the following command in your Nova project:

```bash
composer require ferdiunal/nova-password-confirm-modal
```

## Usage

After installation, you can add the password confirmation field to your Nova resource like this:

<details>

<summary>Code Example</summary>

```php

namespace App\Nova;

use Ferdiunal\NovaPasswordConfirmModal\NovaPasswordConfirmModal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    public static function permissionsForAbilities(): array
    {
        return Permissions::$users;
    }

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),
            Gravatar::make()->maxWidth(50),
            Text::make('Name')
                ->sortable()
                ->maxlength(255)
                ->enforceMaxlength()
                ->rules('required', 'max:255'),

            NovaPasswordConfirmModal::make('Passport Number', 'email')
                ->lockField()
                ->maskChar('#') //  Character to use for masking. (*,#,-,+)
                ->maskIndent(4, 3) // The number of characters to appear at the start or end of the data.
                ->countdown(3) // The data is masked back after the second you provide.,
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}

```

</details>

## License

This project is open-sourced software licensed under the [MIT license](https://github.com/ferdiunal/nova-password-confirm-modal/blob/main/LICENSE).

## Contributing

Contributions are welcome, and any contributors must adhere to the project's code of conduct and licensing terms.

## Support

For support, please open an issue in the GitHub repository.
