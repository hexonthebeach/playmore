<?php
namespace PlayMore;

/**
 * Class GPUMode
 *
 * Mode integer class for controlling GPU operating mode
 */
class GPUMode
{
    /**
     * Disable a GPU
     */
    const DISABLED = 0;

    /**
     * Mine only the main Coin
     */
    const SINGLE = 1;

    /**
     * Mine both Coins
     */
    const DUAL = 2;

}