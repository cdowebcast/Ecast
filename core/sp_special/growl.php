<?php
namespace core\sp_special;


class growl {

    public function writeGrowl($type, $title, $text){
        echo "  <script>
                 $.msgGrowl ({
                        type: '".$type."'
                        , title: '".$title."'
                        , text: '".$text."'
                        , position: $(this).attr ('rel')
                    });
                </script> ";
    }

} 