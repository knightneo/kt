module.export = {
    entry: [
        './src/app.jsx'
    ],
    output: {
        path: './build',
        filename: 'bundle.js',
        publicPath: '/assets/'
    }
}
