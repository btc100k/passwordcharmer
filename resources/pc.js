
function PasswordLists(userInput) {
    this.mWith = [];
    this.mWithout = [];
    this.mSeed = [];
    this.mUserInput = userInput;
}

PasswordLists.IPHONE_SYMBOLS = ['-','/',':',';','(',')','$','&','@','"','.',',','?','!','0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z', '!','+'];
PasswordLists.IPHONE_SYMBOLS_ALT = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','-','/',':',';','(',')','$','&','@','"','.',',','?','!','0','1','2','3','4','5','6','7','8','9', '!','.'];
PasswordLists.ALPHA_ONLY = ['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
PasswordLists.ALPHA_ONLY_ALT = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
PasswordLists.MAXIMUM_PASSWORD_LENGTH = 32

PasswordLists.prototype.reverseString = function(s) {
    var o = '';

    for (var i = s.length - 1; i >= 0; i--) {
        o += s[i];
    }

    return o;
};

PasswordLists.prototype.passwordForChecksum = function(checksum, symbols, start, count) {
    var added = 0;
    var password = "";
    while (added < count) {
        var character = checksum.substring(start, start+2);
        start += 2;
        var value = parseInt(character, 16);
        var in_range_value = value % symbols.length;
        password = password.concat(symbols[in_range_value]);
        added++;
    }
    return password;
};

PasswordLists.prototype.checksumForInput = function(string) {
	var reverseInput = "reversed" + this.reverseString(string) + "reversed"
    return "" + CryptoJS.SHA1(string) + "" + CryptoJS.SHA1(reverseInput);
};

PasswordLists.prototype.createWallet = function(inputEntropy) {
	var Buffer = require('Buffer');
	var bitcore = require('bitcore-lib');
	var Mnemonic = require('bitcore-mnemonic');
	var seedData = Buffer.from(inputEntropy, 'utf8');
	var code = new Mnemonic(seedData, Mnemonic.Words.ENGLISH);
	return code.toString();
};

PasswordLists.prototype.appendPasswordForSalt = function(salt) {
    var input = salt + this.mUserInput + salt;
    var checksum = this.checksumForInput(input);
    var password1 = this.passwordForChecksum(checksum, PasswordLists.IPHONE_SYMBOLS, 0, PasswordLists.MAXIMUM_PASSWORD_LENGTH);
    var password2 = this.reverseString(this.passwordForChecksum(checksum, PasswordLists.IPHONE_SYMBOLS_ALT, 0, PasswordLists.MAXIMUM_PASSWORD_LENGTH));

    var password8 = this.passwordForChecksum(checksum, PasswordLists.ALPHA_ONLY, 0, PasswordLists.MAXIMUM_PASSWORD_LENGTH);
    var password9 = this.passwordForChecksum(checksum, PasswordLists.ALPHA_ONLY_ALT, 0, PasswordLists.MAXIMUM_PASSWORD_LENGTH);

    this.mWith.push(password1);
    this.mWith.push(password2);

    this.mWithout.push(password8);
    this.mWithout.push(password9);
	
    this.mSeed.push(this.createWallet(password1));
    this.mSeed.push(this.createWallet(password2));
};

PasswordLists.prototype.appendPasswordForSaltString = function(saltString, saltLength) {
    for (var i = 0; i < saltString.length; i++) {
        var character = "";
        var appendCount = 0;
        while (appendCount < saltLength) {
            var oneCharacter = saltString.substring(i, i+1);
            character = character.concat(oneCharacter);
            appendCount++;
        }

        this.appendPasswordForSalt(character);
    }
};

var createPasswordLists = function(userInput) {
    if (userInput.length) {
        var lists = new PasswordLists(userInput);
        var $alphabet = "-/;!@#$%^&*";
        lists.appendPasswordForSaltString($alphabet, 1);

        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        lists.appendPasswordForSaltString($alphabet, 2);

        $alphabet = "-/;!@#$%^&*";
        lists.appendPasswordForSaltString($alphabet, 2);

        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        lists.appendPasswordForSaltString($alphabet, 1);

        $alphabet = "-/;!@#$%^&*";
        lists.appendPasswordForSaltString($alphabet, 3);


        $alphabet = "abcdefghijklmnopqrstuvwxyz";
        lists.appendPasswordForSaltString($alphabet, 1);

        lists.appendPasswordForSalt($alphabet, "95");
        lists.appendPasswordForSalt($alphabet, "96");
        lists.appendPasswordForSalt($alphabet, "97");
        lists.appendPasswordForSalt($alphabet, "98");
        lists.appendPasswordForSalt($alphabet, "99");
        lists.appendPasswordForSalt($alphabet, "100");

        var maxLen = lists.mWith[0].length;
        var currentLen = 8;
        var lenValues = [];
        while (currentLen <= maxLen) {
            lenValues.push(currentLen);
            currentLen += 2;
        }

        var response = {
            "with": lists.mWith,
            "without": lists.mWithout,
            "seed": lists.mSeed,
            "count": (lists.mWith.length <= lists.mWithout.length) ? (lists.mWith.length * 2) : (lists.mWithout.length * 2),
            "lengths": {
                "values": lenValues,
                "default": 20
            }
        };

        return response;
    } else {
        return [];
    }
};
