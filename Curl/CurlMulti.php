<?php

    namespace Curl {

        use \SplQueue as SplQueue;
use \Closure as Closure;

        /**
         * @author Gavin Staniforth, http://twitter.com/gsphpdev
         * @version 0.1
         */
        class CurlMulti extends SplQueue
        {

            /**
             * Override psuh() to allow for input for a callback
             * @param Curl $curl
             * @param closure $callback 
             */
            public function push(Curl $curl, Closure $callback=null)
            {
                parent::push(( object ) array('curl' => $curl, 'callback' => $callback));
            }

            /**
             * This will add each Curl into a multi handler then process all,
             * it will either run callbacks for each Curl or return as a Queue of data
             * @return variant
             */
            public function exec()
            {
                if ($this->count() > 0) {
                    $return = new SplQueue;
                    $multi = curl_multi_init();

                    foreach ($this as $value) {
                        curl_multi_add_handle($multi, $value->curl->getResource());
                    }

                    do {
                        $status = curl_multi_exec($multi, $active);
                    } while ($status === CURLM_CALL_MULTI_PERFORM || $active);

                    foreach ($this as $value) {
                        if (($result = curl_multi_getcontent($value->curl->getResource())) !== null) {
                            // If we have a callback lets use it !
                            if ($value->callback !== null) {
                                $callback = $value->callback;
                                $callback($result, $value->curl->getInfo());
                            } else {
                                // Push the data returned into a Queue ready for return
                                $return->push($result);
                            }

                            // Remove handle, Good GC !
                            curl_multi_remove_handle($multi, $value->curl->getResource());
                        }
                    }

                    curl_multi_close($multi);
                    return ($return->count() > 1) ? $return : null;
                }

                throw new CurlException('No curl objects found.', null, null);
            }

        }

    }

    