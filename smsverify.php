<?php

/**
 * Takes care of generating signature for verification and signature comparison.
 */
class SMSverify {

    // Keys that are allowed to use for generating a signature.
    private $allowed_keys = array(
        'user',
        'phone',
        'version',
        'callback_url',
        'sig',
		'status'
    );

    /**
     * Generates signature.
     * 
     * @param array $parameters with required elements for generating a signature.
     * @return string with a signature hash.
     */
    public function generateSignature($parameters, $pass) {
		//Add password param (only needed for signature calculation)
		$parameters['pass'] = $pass;
		
        return $this->hashIt($parameters);
    }

    /**
     * Verifies if signatures match.
     * 
     * @param array $parameters with required elements for generating and verifying signature.
     * @return boolean indicating if the signatures match.
     */
    public function verifySignatures($parameters, $pass) {

        if (!isset($parameters) && empty($parameters)) {
			error_log('verifySignatures parameters param not set!', 3, '/data01/virt49830/domeenid/www.lennar.eu/logs/2fa.log');
            return false;
        }

        if ($parameters['sig'] === $this->generateSignature($parameters, $pass)) {
            return true;
        }
		error_log('Calculated signatures mismatch!');
        return false;
    }

    /**
     * Generates a unique MD5 hash out of alphabetized request parameters.
     * 
     * @param array $parameters with required elements for generating a signature.
     * @return string with hash.
     */
    private function hashIt($parameters) {

        // Sort array by keys
        ksort($parameters);

        $sigstr = '';

        foreach ($parameters as $key => $value) {

            if ((in_array($key, $this->allowed_keys) && $key != 'sig') || $key == 'pass') {
                $sigstr = $sigstr . $key . $value;
            }
        }

        // Create MD5 hash
        $sig = md5($sigstr);
		syslog(LOG_DEBUG, 'Signature string: '.$sigstr);

        return $sig;
    }

}
