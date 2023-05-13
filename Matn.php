<?php

namespace uzdevid\matn;

class Matn extends BaseMatn {
    
    public function correct(): Correct {
        return new Correct(['token' => $this->token]);
    }

    public function suggestions(): Suggestions {
        return new Suggestions(['token' => $this->token]);
    }
}
