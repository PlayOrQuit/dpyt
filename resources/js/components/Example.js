import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import SiteWrapper from "./SiteWrapper.react";

export default class Example extends Component {
    render() {
        return (
            <SiteWrapper>

            </SiteWrapper>
        );
    }
}

if (document.getElementById('root')) {
    ReactDOM.render(<Example />, document.getElementById('root'));
}
