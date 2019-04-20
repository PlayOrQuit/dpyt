import React, { Component } from 'react';
import {
    Page,
    Card,
    Button
} from 'tabler-react';
export default class SiteWrapper extends Component{
    render(){
        return(
            <Page title="Home">
                <Card>
                    <Card.Header>
                        <Card.Title>Card Title</Card.Title>
                    </Card.Header>
                    <Card.Body>
                        <Button color="primary">A Button</Button>
                    </Card.Body>
                </Card>
            </Page>
        );
    }
}