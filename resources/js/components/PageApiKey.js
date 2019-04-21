import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import {
    Container,
    Page,
    Icon,

} from 'tabler-react'

import PageOptions from "tabler-react/dist/components/Page/PageOptions.react";


export default class PageApiKey extends Component{

    render() {
        return(
            <Container>
                <Page.Header>
                    <Page.Title>
                        { trans.get('template.menu_api_key')}
                    </Page.Title>
                    <Page.SubTitle>
                        1 - 20 of 1713 api-key
                    </Page.SubTitle>
                    <PageOptions>
                        abc
                    </PageOptions>
                </Page.Header>
            </Container>
        );
    }
}

if (document.getElementById('section-api-key')) {
    ReactDOM.render(<PageApiKey />, document.getElementById('section-api-key'));
}