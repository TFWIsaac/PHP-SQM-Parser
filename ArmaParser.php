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
            if (preg_match('/^description="([äöüÄÖÜA-Za-z0-9@\s-]+)";$/', $item[0], $m)) {
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
        $tmpGroup = [];
        $slotlist = [];
        $counter = 1;
        foreach ($slotlistArray as $slot) {
            if (strpos($slot, $this->groupChar) !== false) {
                $slotlist[] = $tmpGroup;
                $tmpGroup = [];
                $tmpGroup[] = trim(substr($slot, strpos($slot, $this->groupChar) + 1));
                $tmpGroup[] = trim(strtok($slot, $this->groupChar));
            } else {
                $tmpGroup[] = trim($slot);
            }
        }
        $slotlist[] = $tmpGroup;
        return $this->createTableView($this->createView(array_filter($slotlist)));
    }

    private function createView($slotlist)
    {
        $view = "";
        foreach ($slotlist as $slotgroup) {
            $view .= '<p><strong>Trupp "' . $slotgroup[0] . '"</strong></p>';
            unset($slotgroup[0]);
            $view .= '<ol>';
            foreach ($slotgroup as $key=>$slot) {
                $view .= '<li>' . $slot . '</li>';
            }
            $view .= '</ol>';
        }
        return $view;
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
                    Die Slotliste wurde erfolgreich gelesen! Das Ergebnis kann unten kopiert werden. WICHTIG: Im Forum bitte im Quellcode Modus einfügen!
                    </div><div class="mb-3">
                    <label for="view" class="form-label">Mit der Maus ins Feld klicken, STRG + A drücken und alles kopieren</label>
                    <textarea class="form-control" id="view" rows="5">'.$table.'</textarea>
                    </div>';
    }
}
