# Periodicrypt CLI

periodicrypt-cli is a PHP command line wrapper for the encryption library found here: http://codereview.stackexchange.com/questions/114881/custom-algorithm-for-hashing-and-un-hashing-password

## Installation

Install the [library](http://codereview.stackexchange.com/q/114881) in the same directory as periodicrypt-cli or set the environment variable `$LIBPERIODICRYPTDIR`.

## Usage

Encipher messages.
```
periodicrypt-cli encode foo
[C]|[P]|[P]
```
```
periodicrypt-cli decode "[C]|[P]|[P]"
foo
```

Accepts any number of arguments, quoted or not.
```
periodicrypt-cli encode this message "will self destruct" in 30 seconds.
```

Optionally reads from stdin.
```
periodicrypt-cli encode foo | periodicrypt decode
foo
```
