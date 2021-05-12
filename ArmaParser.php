<?php

/**
 * Class ArmaParser
 * @author Isaac
 */
class ArmaParser
{
    /**
     * @var string[]
     */
    private $colors = [
        'group' => "#4682B4",
        'freeSlot' => "#00FF00",
    ];

    /**
     * @var string
     */
    private $groupChar = "@";

    /**
     * reformts the subarrays keys and reset them keys 0 and 1
     * @param array $playableData
     * @return array
     * @author Isaac
     */
    public function reformatData(array $playableData)
    {
        $slotlistArray = [];
        foreach ($playableData as $item) {
            $item = array_values($item);
            if (preg_match('/^description="([A-Za-z0-9@\s]+)";$/', $item[0], $m)) {
                $slotlistArray[] = $m[1];
            } else {
                $slotlistArray[] = "Keine Slotbeschreibung gefunden!";
            }
        }
        return $slotlistArray;
    }

    /**
     * creates the table based on the slotlist array
     * At this point everybody can edit these lines for their own style
     * @param array $slotlistArray
     * @return string
     * @author Isaac
     */
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

    /**
     * Puts the created Table into an inputfield.
     * @param $table
     * @return string
     * @author Isaac
     */
    public function createTableView($table)
    {
       return '<br><br><div class="alert alert-success" role="alert">
                    Die Slotliste wurde erfolgreich gelesen! Das Ergebnis kann unten kopiert werden.
                    </div><div class="mb-3">
                    <label for="view" class="form-label">Mit der Maus ins Feld klicken, STRG + A drücken und alles kopieren</label>
                    <textarea class="form-control" id="view" rows="5">'.$table.'</textarea>
                    </div>';
    }
}
