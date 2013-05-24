var oldMerchantInfo = $('longmerintro').childNodes[0].innerHTML;

			var folderInfo = function (length) {
				var word_length = (length) ? length : 160;
				var merchantInfo = $('longmerintro').childNodes[0].innerHTML;
				var prefix = '<a href=\'javascript:void(0)\' onclick=\'unfolderInfo(' + word_length + ')\'>¸ü¶à</a>';
				var string = merchantInfo.stripTags();
				if (word_length > string.length) return;
				var pos = word_length;
				while (pos < string.length) {
					var w = string.slice(pos, pos+1);
					if (w == ' ' || w == '¡£') break;
					pos++;
				}
				merchantInfo = string.slice(0, pos) + ' ... ' + prefix;
				$('longmerintro').innerHTML = '<p>' + merchantInfo + '</p>';
			}
			
			var unfolderInfo = function (length) {
				var word_length = (length) ? length : 160;
				var prefix = '&nbsp;<a href=\'javascript:void(0)\' onclick=\'folderInfo(' + word_length + ')\'>Òþ²Ø</a>';
				$('longmerintro').innerHTML = '<p>' + oldMerchantInfo + prefix + '</p>';
			}