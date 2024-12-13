bs.util.registerNamespace( 'bs.interwikilinks.ui.page' );

bs.interwikilinks.ui.page.InterWikiLinksPage = function ( cfg ) {
	cfg = cfg || {};
	this.title = cfg.data.title || mw.message( 'bs-interwikilinks-titleaddinterwikilink' ).plain();
	this.oldPrefix = cfg.data.oldPrefix || '';

	bs.interwikilinks.ui.page.InterWikiLinksPage.parent.call( this, 'interwikilinks', cfg );
};

OO.inheritClass( bs.interwikilinks.ui.page.InterWikiLinksPage, OOJSPlus.ui.booklet.DialogBookletPage );

bs.interwikilinks.ui.page.InterWikiLinksPage.prototype.getItems = function () {
	this.prefix = new OO.ui.TextInputWidget();
	this.url = new OO.ui.TextInputWidget();

	this.prefixLayout = new OO.ui.FieldLayout( this.prefix, {
		label: mw.message( 'bs-interwikilinks-labelprefix' ).plain(),
		align: 'left'
	} );
	this.urlLayout = new OO.ui.FieldLayout( this.url, {
		label: mw.message( 'bs-interwikilinks-labelurl' ).plain(),
		align: 'left'
	} );

	return [
		this.prefixLayout,
		this.urlLayout
	];
};

bs.interwikilinks.ui.page.InterWikiLinksPage.prototype.setData = function ( data ) {
	if ( data.hasOwnProperty( 'prefix' ) ) { // eslint-disable-line no-prototype-builtins
		this.prefix.setValue( data.prefix );
	}
	if ( data.hasOwnProperty( 'url' ) ) { // eslint-disable-line no-prototype-builtins
		this.url.setValue( data.url );
	}
};

bs.interwikilinks.ui.page.InterWikiLinksPage.prototype.getTitle = function () {
	return this.title;
};

bs.interwikilinks.ui.page.InterWikiLinksPage.prototype.getActionKeys = function () {
	return [ 'cancel', 'done' ];
};

bs.interwikilinks.ui.page.InterWikiLinksPage.prototype.getAbilities = function () {
	return { done: true, cancel: true };
};

bs.interwikilinks.ui.page.InterWikiLinksPage.prototype.onAction = function ( action ) {
	const dfd = $.Deferred();

	if ( action === 'done' ) {
		bs.api.tasks.execSilent(
			'interwikilinks',
			'editInterWikiLink',
			{
				prefix: this.prefix.getValue(),
				url: this.url.getValue(),
				oldPrefix: this.oldPrefix
			}, {
				success: () => {
					dfd.resolve( { action: 'close', data: { success: true } } );
				},
				failure: ( response ) => {
					dfd.reject( response.message );
				}
			}
		);
	} else {
		return bs.interwikilinks.ui.page.InterWikiLinksPage.parent.prototype.onAction.call( this, action );
	}

	return dfd.promise();
};
