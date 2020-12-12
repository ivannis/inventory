import React, {useState} from 'react';
import { Tabs, Panel } from '@bumaga/tabs'
import Layout from '../Layouts/Layout';
import Header from '../Components/Header';
import Alert from '../Components/Alert';
import Tab from '../Components/Tab';
import ProductsContainer from '../Containers/Products'
import StockContainer from '../Containers/Stock'
import InventoryContainer from '../Containers/Inventory'
import StockPurchaseContainer from '../Containers/Stock/Purchase'
import StockApplyContainer from '../Containers/Stock/Apply'
import StockItemContainer from '../Containers/Stock/Items'
import './Welcome.css';

export default function Welcome() {
    const [formIndex, setFormIndex] = useState(1);
    const [inventoryIndex, setInventoryIndex] = useState(1);

    const [product, setProduct] = useState(null);

    const onProductSelected = (selected) => {
        setProduct(selected)
    }

    const renderStock = () => {
        if (!product) {
            return (
                <Alert type='warning'>Please, select one product from the list.</Alert>
            )
        }

        return (
            <React.Fragment>
                <Tabs state={[formIndex, setFormIndex]}>
                    <div className='tabs'>
                        <div className='tab-list'>
                            <Tab>Apply</Tab>
                            <Tab>Purchase</Tab>
                        </div>
                    </div>

                    <Panel>
                        <StockApplyContainer product={product}/>
                    </Panel>
                    <Panel>
                        <StockPurchaseContainer product={product}/>
                    </Panel>
                </Tabs>
                <StockContainer product={product}/>

                <Tabs state={[inventoryIndex, setInventoryIndex]}>
                    <div className='tabs'>
                        <div className='tab-list small'>
                            <Tab>Inventory history</Tab>
                            <Tab>Inventory items</Tab>
                        </div>
                    </div>
                    <Panel>
                        <InventoryContainer product={product} />
                    </Panel>
                    <Panel>
                        <StockItemContainer product={product}/>
                    </Panel>
                </Tabs>
            </React.Fragment>
        );
    }

    return (
        <Layout title='Inventory app'>
            <Header
              title='Welcome to inventory'
              description='Get started by selecting a product on the list'
            />
            <div className="content">
                <div className="left">
                    <ProductsContainer onSelected={onProductSelected}/>
                </div>
                <div className="right">
                    {renderStock()}
                </div>
            </div>
        </Layout>
    );
}
