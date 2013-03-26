<?php
/**
 * Helper functions for working with ID strings
 */

class ID extends AbstractObject{

    /**
     * Generate a random ID of length 1-32 alpha-numeric chars
     *
	 * @param  int  $length
	 * @return string
     */
    public static function alphanumeric($length = 32)
    {
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
            'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
            'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
            'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
            'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7',
            '8', '9');

        return self::generate($length, $chars);
    }

    /**
     * Generate a random ID of length 1-32
     * legible upper-case alpha chars
     *
	 * @param  int  $length
	 * @return string
     */
    public static function legible_alpha($length = 3)
    {
        $chars = array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
            'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Z'
            );

        return self::generate($length, $chars);
    }

    /**
     * Generate a random ID of 3 digits &
     * 3 legible alphas
     *
     * Useful for autoship ids, site visit
     * ids etc
     *
	 * @return string
     */
    public static function mixed()
    {
        $alpha = self::legible_alpha(3);
        $num = mt_rand(100, 999);

        return $alpha . $num;
    }

    /**
     * Generate a random ID of 3 legible alphas &
     * 3 digits
     *
     * Useful for autoship ids, site visit
     * ids etc
     *
	 * @return string
     */
    public static function mixed_rev()
    {
        $alpha = self::legible_alpha(3);
        $num = mt_rand(100, 999);

        return $num . $alpha;
    }

    /**
     * Calls generating a random ID and checks if the ID exist in the table
     * @param string $model
     * @return string|boolean
     */
    public function generateMixedAndCheck($model){
        for($i=0;$i<100;$i++){
            $id=self::mixed();
            $mod=$this->add($model);
            $res=$mod->tryLoad($id);
            if(!$res->loaded()){
                return $id;
                break;
            }
        }
        return false;
    }

    /**
     * Calls generating a random ID and checks if the ID exist in the table
     * @param string $model
     * @return string|boolean
     */
    public function generateMixedRevAndCheck($model){
        for($i=0;$i<100;$i++){
            $id=self::mixed_rev();
            $mod=$this->add($model);
            $res=$mod->tryLoad($id);
            if(!$res->loaded()){
                return $id;
                break;
            }
        }
        return false;
    }

    /**
     * Generate an id from the character array
     * provided.
     *
	 * @param  int  $length
	 * @param  array  $chars
	 * @return string
     */
    protected static function generate($length = 32, array $chars)
    {
        // Sanitise params

        if( $length < 0 )
        {
            $length = 1 ;
        }
        elseif( $length > 32 )
        {
            // No practical need for anything longer

            $length = 32 ;
        }

        $id = '';
        $max = count($chars) -1;

        for( $i=1; $i <= $length; $i++)
        {
            $num = mt_rand(0, $max);
            $id .= $chars[$num];
        }

        return $id;
    }
}
