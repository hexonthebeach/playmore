# PlayMore

A PHP library for remote management of ClayMore miners.

Since nobody knows when Ethereum is going to switch to PoS this project just might prove valuable to someone. 

## Getting Started

Composer require or download this repo to your project and get it on.
```
$playmore = new PlayMore\PlayMore();
$playmore->connect('192.168.1.20', 3334, 'P*ssw0rd'); // bool 
```

## Prerequisites

PHP 5.4 or higher, and maybe a ClayMore miner client (https://bitcointalk.org/index.php?topic=1433925.0)


## You Can Now

Manage a miner with an instance of the PlayMore class, like this

* Test the connection availability
```
$playmore->test(); // bool
```

* Get normal or detailed statistics like you see in EthMan
```
$playmore->status(); // array

$playmore->details(); // array
```

* Restart the Miner client, or Reboot the machine
```
$playmore->restart(); // bool
$playmore->reboot(); // bool
```

* Change the Mode of any GPU by index from the status call (single/dual/disabled)
```
$playmore->setGpuMode($gpuIdx, PlayMore\GPUMode::SINGLE); // bool
```

* Read and Write the Config- Epools- and Dpools- files
```
$playmore->config(); // string
$playmore->setConfig($newConfigContent); // bool

$playmore->epools(); // string
$playmore->setEpools($newEpoolsContent); // bool

$playmore->dpools(); // string
$playmore->setDpools($newDpoolsContent); // bool
```

* And ...
```
$playmore->disconnect(); // bool
```

## Contributing

Please do not hesitate to help out improving code in this project.

Or just send some ❤ 0x99DD98ee91f19aBc33474e8ca7c900d23395f412

## Authors

* [HexOnTheBeach](https://github.com/hexonthebeach)

Complete list of [contributors](https://github.com/hexonthebeach/playmore/contributors)