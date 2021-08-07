import React, { useEffect } from 'react';
import ReactDOM from 'react-dom';

function Entrypoint() {

    useEffect( () => {
        document.title = 'YARA'
    }, []);

    return (
        <div className="container">
            <div className="row justify-content-center">
                HELLO
            </div>
        </div>
    );
}

export default Entrypoint;

if (document.getElementById('root')) {
    ReactDOM.render(<Entrypoint />, document.getElementById('root'));
}
