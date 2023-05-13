<?php

namespace uzdevid\korrektor;

class Matn extends BaseMatn {
    
    public function correct(): Correct {
        return new Correct(['token' => $this->token]);
    }
}
