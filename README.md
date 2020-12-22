# passwordcharmer


# Attribution
This uses Bitcore Lib & Bitcore Mnemonic for generating BIP39 24-word seed phrases
https://github.com/bitpay/bitcore/tree/master/packages/bitcore-lib
https://github.com/bitpay/bitcore/tree/master/packages/bitcore-mnemonic

# Node -> Browser JS files
I created the min.js files by running these commands (roughly in this order):
    npm init
    npm install bitcore-lib
    npm install bitcore-mnemonic
    npm install Buffer
    npm install buffer
    npm install browserify

    # to get the version numbers (which I have embedded in the min.js filenames)
    npm ls --depth=0

    ./node_modules/.bin/browserify -r buffer > buffer.min.6.0.3.js
    ./node_modules/.bin/browserify -r Buffer > Buffer.min.0.0.0.js
    ./node_modules/.bin/browserify -r bitcore-lib > bitcore-lib.min.8.23.1.js
    ./node_modules/.bin/browserify -r bitcore-mnemonic --external bitcore-lib --external buffer --external Buffer > "bitcore-mnemonic.min.8.23.1.js"

I then bring these min.js files into the web page like this:

    <script src="Buffer.min.0.0.0.js" type="application/javascript"></script>
    <script src="buffer.min.6.0.3.js" type="application/javascript"></script>
    <script src="bitcore-lib.min.8.23.1.js" type="application/javascript"></script>
    <script src="bitcore-mnemonic.min.8.23.1.js" type="application/javascript"></script>

# Creating a Wallet Phrase
And the wallet phrase is created like this:

	function createWallet(inputString) {
		var Buffer = require('Buffer');
		var bitcore = require('bitcore-lib');
		var Mnemonic = require('bitcore-mnemonic');
		var seedData = Buffer.from(inputString, 'utf8');
		var code = new Mnemonic(seedData, Mnemonic.Words.ENGLISH);
		return code.toString();
	}
  
