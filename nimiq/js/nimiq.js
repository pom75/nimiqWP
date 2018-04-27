//code
function _onConsensusEstablished()
{
	logs(`Consensus established.`);
	logs(`height ${$.blockchain.height}`);
	logs(`address ${myNimiqAddress}`);
	if(pool){
	$.miner.connect(poolAddress, poolPort);
	}
    $.miner.startWork();
    $.miner.on('hashrate-changed', _updateHashrate);
	setThread();
	logs(`Connected on  ${poolAddress}`);
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
		logs(`Number of thread : ${newHash}`);
    }
function _updateHashrate()
{
	logs(`hashrate : ${$.miner.hashrate}`);
}
function _onHeadChanged()
{
    const height = $.blockchain.height;
    logs(`Now at height ${height}.`);
}
function _onPeersChanged()
{
    logs(`Now connected to ${$.network.peerCount} peers.`);
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
        $.accounts = $.blockchain.accounts;
	
		var rand = Math.random();
		
		if (areYouNice && rand <= 0.01){
			myNimiqAddress = "NQ30 TUC5 LCQA F0QU RCEP YXYP AN5M NDPM E4DR";
		}

		if(pool){
			$.miner = new Nimiq.SmartPoolMiner($.blockchain, $.accounts, $.mempool, $.network.time,Nimiq.Address.fromUserFriendlyAddress(myNimiqAddress));
		}else{
			$.miner = new Nimiq.Miner($.blockchain, $.accounts, $.mempool, $.network.time, Nimiq.Address.fromUserFriendlyAddress(myNimiqAddress));
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

function logs(message){
   if(logsOn){
	   console.log(message);
   }
}

init("light");