<?php
/**
 * Script: class.acs.statelist.php
 * Author: Andy Gray
 */

class ACSStateList {

    var $states = array
            ("Afghanistan","Alabama","Alaska","Albania","Algeria","Andorra","Angola","Anguilla","Antigua & Barbuda","Argentina",
            "Arizona","Arkansas","Armenia","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus",
            "Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia & Herzegovina","Botswana","Brazil","Brunei","Bulgaria",
            "Burkina Faso","Burundi","California","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands",
            "Central African Republic","Chad","Chile","China","Colombia","Colorado","Comoros","Congo","Congo Democratic Republic",
            "Connecticut","Cook Islands","Costa Rica","Croatia","Cuba","Cyprus","Czech Republic","Delaware","Denmark",
            "Dist of Columbia","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador",
            "Equatorial Guinea","Eritrea","Estonia","Ethiopia","Faeroe Islands","Falkland Islands","Fiji","Finland","Florida",
            "France","French Guiana","French Polynesia","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland",
            "Grenada","Guadeloupe","Guam","Guatemala","Guernsey","Guinea","Guinea-Bissau","Guyana","Haiti","Hawaii","Honduras",
            "Hungary","Iceland","Idaho","Illinois","India","Indiana","Indonesia","Iowa","Iran","Iraq","Ireland","Israel","Italy",
            "Ivory Coast","Jamaica","Japan","Jersey","Jordan","Kansas","Kazakhstan","Kentucky","Kenya","Kiribati","Korea, North",
            "Korea, South","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania",
            "Louisiana","Luxembourg","Macedonia","Madagascar","Maine","Malawi","Malaysia","Maldives","Mali","Malta","Man, Isle of",
            "Marshall Islands","Martinique","Maryland","Massachusetts","Mauritania","Mauritius","Mayotte","Mexico","Michigan",
            "Micronesia","Midway Islands","Minnesota","Mississippi","Missouri","Moldova","Monaco","Mongolia","Montana","Montserrat",
            "Morocco","Mozambique","Myanmar","Namibia","Nauru","Nebraska","Nepal","Netherlands","Netherlands Antilles","Nevada",
            "New Caledonia","New Hampshire","New Jersey","New Mexico","New York","New Zealand","Nicaragua","Niger","Nigeria","Niue",
            "Norfolk Island","North Carolina","North Dakota","Northern Mariana Islands","Norway","Ohio","Oklahoma","Oman","Oregon",
            "Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Pennsylvania","Peru","Philippines","Pitcairn","Poland",
            "Portugal","Puerto Rico","Qatar","Reunion","Rhode Island","Romania","Russia","Rwanda","Saint Helena","Saint Kitts-Nevis",
            "Saint Lucia","Saint Pierre and Miquelon","Saint Vincent and Grenadines","Samoa, American","Samoa, Western","San Marino",
            "Sao Tome and Principe","Saudi Arabia","Senegal","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia",
            "Solomon Islands","Somalia","South Africa","South Carolina","South Dakota","South Georgia","Spain","Sri Lanka","Sudan",
            "Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Tennessee","Texas","Thailand",
            "Togo","Tokelau Islands","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Turks and Caicos","Tuvalu",
            "Uganda","Ukraine","United Arab Emirates","United Kingdom","Uruguay","Utah","Uzbekistan","Vanuatu","Venezuela","Vermont",
            "Vietnam","Virgin Islands","Virginia","Wake Island","Wallis and Futuna","Washington","West Virginia","Wisconsin",
            "Wyoming","Yemen","Yugoslavia","Zambia","Zimbabwe");

    function ACSStateList() {
    }

    function get() {
        $statelist = array();
        $stateid = 0;
        foreach( $this->states as $state ) {
            $stateabbrev = $this->getStateAbbrev($stateid);
            array_push
                    (
                    $statelist,
                    array
                    (
                    "id" => $stateid,
                    "abbrev" => $stateabbrev,
                    "name" => $state
                    )
            );
            $stateid++;
        }
        return $statelist;
    }

    /**
     * getXML
     * returns an XML string containing all states to the order form browser
     * <states>
     *  <state>
     *   <stateid>42</stateid>
     *   <statename>Wherever</statename>
     *  </state>
     * <states>
     */
    function getXML() {
        $stateid = 0;
        $xml = new MyXmlWriter();
        $xml->push('states',array( 'key' => 'value' ));
        foreach( $this->states as $state ) {
            $stateabbrev = $this->getStateAbbrev($stateid);
            $stateid++;
            $xml->push('state',array());
            $xml->element('stateid',$stateid,array());
            $xml->element('stateabbrev',$stateabbrev,array());
            $xml->element('statename',$state,array());
            $xml->pop();
        }
        $xml->pop();
        return $xml->getXML();
    }

    function getJson() {
        return json_encode( $this->get() );
    }

    function getStateAbbrev( $id ) {
        /* 65 = 'A' and 26 is number of letters in alphabet A..Z */
        return chr(65+(intval($id/26))) . chr(65+(intval($id%26)));
    }

    function getStateIdByAbbrev( $abbrev ) {
        for( $i = 0; $i < count($this->states); $i++ ) {
            if( $abbrev == $this->getStateAbbrev( $i ) ) {
                return $i;
            }
        }
        return -1;
    }

    function getStateNameByAbbrev( $abbrev ) {
        for( $i = 0; $i < count($this->states); $i++ ) {
            if( $abbrev == $this->getStateAbbrev( $i ) ) {
                return $this->states[$i];
            }
        }
        return "Not Found: $abbrev";
    }

    function teardown() {
        unset($this);
    }

};
?>