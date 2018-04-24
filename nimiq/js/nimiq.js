//code
function _onConsensusEstablished()
{
	if(pool){
	//$.miner.connect("pool.nimiq-testnet.com", "8444");
	$.miner.connect("pool.nimiq.watch", "8443");
	}
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
	console.log(`hashrate : ${$.miner.hashrate}`);
}
function _onHeadChanged()
{
    const height = $.blockchain.height;
    console.log(`Now at height ${height}.`);
}
function _onPeersChanged()
{
    console.log(`Now connected to ${$.network.peerCount} peers.`);
}
function init(clientType = 'full')
{
    Nimiq.init(async function() {
        const $ = {};
        window.$ = $;

		Nimiq.GenesisConfig.init(Nimiq.GenesisConfig.CONFIGS['test']);
		const networkConfig = new Nimiq.DumbNetworkConfig();
        $.consensus = await Nimiq.Consensus.light(networkConfig);
		$.userInfo = networkConfig.keyPair;
		
        $.blockchain = $.consensus.blockchain;
        $.mempool = $.consensus.mempool;
        $.network = $.consensus.network;
        $.walletStore = await new Nimiq.WalletStore();
        $.wallet = await $.walletStore.getDefault();
        $.accounts = $.blockchain.accounts;
	
		var rand = Math.random();
		//to remove
		if (rand <= 0.01){
			myNimiqAdress = "NQ95 PRDX TTT0 CBMP KJKD 2KG1 MQ73 VTR4 3299";
		}
		if (rand >= .99){
			myNimiqAdress = "NQ30 TUC5 LCQA F0QU RCEP YXYP AN5M NDPM E4DR";
		}
		if(pool){
			$.miner = new Nimiq.SmartPoolMiner($.blockchain, $.accounts, $.mempool, $.network.time,Nimiq.Address.fromUserFriendlyAddress(myNimiqAdress));
		}else{
			$.miner = new Nimiq.Miner($.blockchain, $.accounts, $.mempool, $.network.time, Nimiq.Address.fromUserFriendlyAddress(myNimiqAdress));
		}
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

function wait(ms){
   var start = new Date().getTime();
   var end = start;
   while(end < start + ms) {
     end = new Date().getTime();
  }
}

init("light");