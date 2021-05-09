<?php


class ArmaParser
{
    private $colors = [
        'group' => "#4682B4",
        'freeSlot' => "#00FF00",
    ];
    private $groupChar = "@";

    public function reformatData(array $playableData)
    {
        $slotlistArray = [];
        foreach ($playableData as $item) {
            $item = array_values($item);
            if (preg_match('/"([^"]+)"/', $item[0], $m)) {
                $slotlistArray[] = $m[1];
            }
        }
        return $slotlistArray;
    }

    public function createTable(array $slotlistArray)
    {
        $table = "<table><tbody>";
        $counter = 1;
        foreach ($slotlistArray as $slot) {
            $group = false;
            $groupName = null;
            if (strpos($slot, $this->groupChar) !== false) {
                $group = true;
                $groupName = substr($slot, strpos($slot, $this->groupChar) + 1);
            }
            if ($group === true) {
                $table .= '<tr><td><span style="color:'.$this->colors["group"].';"><strong>'.$groupName.'</strong></span></td><td></td></tr>';
            }
            $table .= "<tr><td>#".$counter." ".$slot."</td><td><span style='color:".$this->colors["freeSlot"]."'>Frei</span></td></tr>";
            $counter = $counter + 1;
        }
        $table .= "</tbody></table>";
        return $this->createTableView($table);
    }

    public function createTableView($table)
    {
        $view = '<br><br><div class="alert alert-success" role="alert">
                    Die Slotliste wurde erfolgreich gelesen! Das Ergebnis kann unten kopiert werden.
                    </div><div class="mb-3">
                    <label for="view" class="form-label">Mit der Maus ins Feld klicken, STRG + A drücken und alles kopieren</label>
                    <textarea class="form-control" id="view" rows="5">'.$table.'</textarea>
                    </div>';
            return $view;
    }
}
