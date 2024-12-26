<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\File;

class SlideShow extends Component
{
    public $imagePaths = [];

    public function mount()
    {
        $this->loadSlideshowImages();
    }


    public function loadSlideshowImages()
    {
        $imageDirectory = public_path('img/logo');
        $images = File::files($imageDirectory);

        foreach ($images as $image) {
            $this->imagePaths[] = 'img/logo/' . $image->getFilename();
        }
    }

    public function render()
    {
        return view('livewire.slide-show', [
            'imagePaths' => $this->imagePaths,
        ]);
    }
  
}
