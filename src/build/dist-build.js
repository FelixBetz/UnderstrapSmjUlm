const { promises: fs } = require( 'fs' );
const path = require( 'path' );
const { execFile } = require( 'child_process' );
const { promisify } = require( 'util' );
const pkg = require( '../../package.json' );

const execFileAsync = promisify( execFile );

async function copyDir( src, dest ) {
	await fs.mkdir( dest, { recursive: true } );
	let entries = await fs.readdir( src, { withFileTypes: true } );
	// Exclude all dot files and directories.
	entries = entries.filter( dirent => ! dirent.name.startsWith('.') );
	const ignore = [
		'dist',
		'node_modules',
		'src',
		'vendor',
		'composer.json',
		'composer.lock',
		'package.json',
		'package-lock.json',
		'phpcs.xml.dist',
		'phpmd.baseline.xml',
		'phpmd.xml',
		'phpstan-baseline.neon',
		'phpstan.neon.dist',
		'README.md',
		'copy.bat',
	];

	for ( const entry of entries ) {
		if ( ignore.indexOf( entry.name ) != -1 ) {
			continue;
		}
		let srcPath = path.join( src, entry.name );
		let destPath = path.join( dest, entry.name );

		entry.isDirectory()
			? await copyDir( srcPath, destPath )
			: await fs.copyFile( srcPath, destPath );
	}
}

async function buildDist() {
	const destination = `./dist/${ pkg.name }`;
	const zipPath = `./dist/${ pkg.name }.zip`;
	const distRoot = path.resolve( './dist' );
	const zipPathAbsolute = path.resolve( zipPath );

	await fs.rm( destination, { recursive: true, force: true } );
	await fs.rm( zipPath, { force: true } );
	await fs.mkdir( destination, { recursive: true } );
	await copyDir( './', destination );

	try {
		await execFileAsync( 'tar', [
			'-a',
			'-c',
			'-f',
			zipPathAbsolute,
			'-C',
			distRoot,
			pkg.name,
		] );
	} catch ( error ) {
		if ( process.platform !== 'win32' ) {
			throw error;
		}

		const sourcePath = path.resolve( destination );

		await execFileAsync( 'powershell', [
			'-NoProfile',
			'-Command',
			`Compress-Archive -Path "${ sourcePath }" -DestinationPath "${ zipPathAbsolute }" -Force`,
		] );
	}
}

buildDist().catch( error => {
	console.error( error );
	process.exit( 1 );
} );