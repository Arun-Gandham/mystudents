<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestFormController extends Controller
{
    public function create()
    {
        return view('shared.test.test-form', [
            'formSchema' => $this->schema(),
            'formData'   => $this->formData(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = $this->rulesFromSchema($this->schema());
        $data  = $request->validate($rules);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('test', 'public');
        }

        $data['tags']       = array_values($data['tags'] ?? []);
        $data['facilities'] = array_values($data['facilities'] ?? []);
        $data['is_active']  = (bool)($data['is_active'] ?? false);

        return back()
            ->withInput()
            ->with('success', 'Submitted! (Test only — not saved to DB)')
            ->with('posted', $data);
    }

    private function schema(): array
    {
        return [
            'submit_text' => 'Save (Test)',
            'fields' => [
                // Basics
                ['type'=>'text','name'=>'school_name','label'=>'School Name','required'=>true,'track'=>true,'col'=>'col-md-6','attrs'=>['maxlength'=>150]],
                ['type'=>'email','name'=>'email','label'=>'Email','required'=>true,'track'=>true,'col'=>'col-md-6'],
                ['type'=>'password','name'=>'password','label'=>'Password','col'=>'col-md-6'],
                ['type'=>'tel','name'=>'phone','label'=>'Phone','track'=>true,'col'=>'col-md-6','attrs'=>['placeholder'=>'+971...', 'pattern'=>'^\+?[0-9]{7,15}$']],
                ['type'=>'number','name'=>'strength','label'=>'Total Students','col'=>'col-md-6','track'=>true,'attrs'=>['min'=>'0','step'=>'1']],

                // Dates / times / web / color
                ['type'=>'date','name'=>'established_at','label'=>'Established Date','col'=>'col-md-3','track'=>true],
                ['type'=>'time','name'=>'opening_time','label'=>'Opening Time','col'=>'col-md-3'],
                ['type'=>'url','name'=>'website','label'=>'Website','col'=>'col-md-3','attrs'=>['placeholder'=>'https://...']],
                ['type'=>'color','name'=>'brand_color','label'=>'Brand Color','col'=>'col-md-3'],

                // Toggles
                ['type'=>'switch','name'=>'is_active','label'=>'Active','col'=>'col-md-4','default'=>true],
                ['type'=>'checkbox','name'=>'agree','label'=>'Agree to Terms','col'=>'col-md-4','track'=>true],

                // Choice groups
                ['type'=>'radio','name'=>'level','label'=>'Level','required'=>true,'col'=>'col-md-12','track'=>true,
                  'options'=>['primary'=>'Primary','secondary'=>'Secondary','k12'=>'K-12']],
                ['type'=>'select','name'=>'type','label'=>'Type','col'=>'col-md-6','track'=>true,'options'=>'types'],
                ['type'=>'multiselect','name'=>'tags','label'=>'Tags','col'=>'col-md-6','options'=>'tags','size'=>5],
                ['type'=>'checkbox_group','name'=>'facilities','label'=>'Facilities','col'=>'col-md-12','options'=>'facilities'],

                // Long text
                ['type'=>'textarea','name'=>'description','label'=>'Description','rows'=>3,'col'=>'col-md-12'],

                // File w/ preview
                ['type'=>'file','name'=>'logo','label'=>'Logo','col'=>'col-md-6','attrs'=>['accept'=>'.jpg,.jpeg,.png,.webp'],'preview'=>'#logoPreview'],
            ],
        ];
    }

    private function formData(): array
    {
        return [
            'types' => ['public'=>'Public','private'=>'Private','charter'=>'Charter'],
            'tags' => ['new'=>'New','top'=>'Top-rated','urban'=>'Urban','rural'=>'Rural','coed'=>'Co-Ed'],
            'facilities' => ['lab'=>'Laboratory','library'=>'Library','sports'=>'Sports','transport'=>'Transport'],
        ];
    }

    private function rulesFromSchema(array $schema): array
    {
        $rules = [];
        foreach ($schema['fields'] as $f) {
            $name = $f['name'] ?? null; if (!$name) continue;
            $r = [];
            if (!empty($f['required'])) $r[] = 'required';

            switch ($f['type'] ?? 'text') {
                case 'email':  $r[]='email'; $r[]='max:255'; break;
                // case 'password': $r[]='string'; $r[]='min:6'; break;
                // case 'tel':    $r[]='regex:/^\+?[0-9]{7,15}$/'; break;
                // case 'number': $r[]='numeric'; break;
                // case 'date':   $r[]='date'; break;
                // case 'time':   $r[]='date_format:H:i'; break;
                // case 'url':    $r[]='url'; break;
                // case 'file':   $r[]='nullable'; $r[]='file'; $r[]='mimes:jpg,jpeg,png,webp'; $r[]='max:2048'; break;
                // case 'multiselect':
                // case 'checkbox_group': $r[]='array'; break;
                default: break;
            }

            $rules[$name] = $r;
        }
        // narrow allowed values (optional)
        $rules['tags.*']       = ['in:new,top,urban,rural,coed'];
        $rules['facilities.*'] = ['in:lab,library,sports,transport'];

        return $rules;
    }
}
