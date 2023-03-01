<?

Class Player{
    public $name;
    public $coins;

    public function __construct($name, $coins)
    {
        $this->name = $name;
        $this->coins = $coins;
    }

    public function point($player)
    {
        $this->coins++;
        $player->coins--;
    }

    public function bankrupt()
    {
        return $this->coins == 0;
    }

    public function bank(){
        return $this->coins;
    }

    public function odds($player)
    {
        return round($this->bank() / ($this->bank() + $player->bank()), 2) * 100 . "%";
    }
}

class Game{
    protected $player1;
    protected $player2;
    protected $flips = 1;

    public function __construct($player1, $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    public function flip()
    {
        return rand(0, 1) ? "орел" : "решка";
    }

    public function start()
    {
        echo <<<EOT
            Шансы первого игрока: {$this->player1->odds($this->player2)}
            Шансы второго игрока: {$this->player2->odds($this->player1)}

        EOT;
        $this->play();
    }

    public function play()
    {
        while(true){
            // Подбросить монету.
            // Если орел, п1 получает монету, п2 теряет.
            // Если решка, п2 получает монету, п1 теряет.
            if($this->flip() == "орел"){ 
                $this->player1->point($this->player2);
            }else{
                $this->player2->point($this->player1);
            }
            // Если кто - то теряет все монеты то игра закончена. Победитель тот у кого больше монет.
            if($this->player1->bankrupt() || $this->player2->bankrupt()){
                return $this->end();
            }
            $this->flips++;
        }
    }

    public function winner(): Player
    {
        return ($this->player1->bank() > $this->player2->bank()) ? $this->player1 : $this->player2;
    }

    public function end()
    {
        // Победитель тот у кого больше монет
        echo <<<EOT

            {$this->player1->name}:{$this->player1->bank()}
            {$this->player2->name}:{$this->player2->bank()}

            Победитель: {$this->winner()->name}
            Количество подбрасываний: {$this->flips}
            Game over.
        EOT;
    }
}

$game = new Game(
    new Player("Joe", 200),
    new Player("Jon", 3300),
);

$game->start();